<?php

namespace App\Http\Controllers;

use App\Models\NegotiationMessage;
use App\Models\Notification;
use App\Models\Penawaran;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NegotiationController extends Controller
{
    /**
     * Ambil seluruh riwayat chat negosiasi untuk sebuah penawaran.
     * Setelah deal (freelancer dipilih), negosiasi tetap bisa dibaca (read-only)
     * namun tidak bisa menambah pesan baru. Informasi lock + ringkasan deal
     * + link workspace dikembalikan agar modal dapat menampilkan banner yang jelas.
     */
    public function getMessages(Penawaran $penawaran): JsonResponse
    {
        $this->authorizeAccess($penawaran);

        // Membuka percakapan = membaca pesan: tandai notifikasi negosiasi
        // milik user ini untuk penawaran INI saja sebagai sudah dibaca,
        // memakai mekanisme read/unread Notification yang sudah ada.
        Notification::where('user_id', Auth::id())
            ->where('type', 'negotiation.message')
            ->where('penawaran_id', $penawaran->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages = NegotiationMessage::with('sender:id,name')
            ->where('penawaran_id', $penawaran->id)
            ->orderBy('created_at')
            ->get()
            ->map(function (NegotiationMessage $msg) {
                return $this->serialize($msg);
            });

        $isLocked = $this->isNegotiationLocked($penawaran);
        $project  = $penawaran->project;
        $workspace = $project ? \App\Models\Workspace::where('project_id', $project->id)->first() : null;

        // Workspace URL sesuai role user saat ini (company / freelancer terpilih)
        $workspaceUrl = null;
        if ($workspace) {
            // Hanya berikan workspace_url jika user saat ini adalah peserta workspace (company pemilik atau freelancer terpilih)
            // Freelancer yang ditolak tidak mendapat link workspace (tetap bisa lihat riwayat read-only, tapi 403 jika coba buka workspace)
            if ((int) $workspace->company_id === (int) Auth::id()) {
                $workspaceUrl = route('company.workspaces.show', $workspace);
            } elseif ((int) $workspace->freelancer_id === (int) Auth::id()) {
                $workspaceUrl = route('freelancer.workspaces.show', $workspace);
            } else {
                $workspaceUrl = null;
            }
        }

        return response()->json([
            'success'  => true,
            'messages' => $messages,
            'penawaran' => [
                'id'             => $penawaran->id,
                'harga_penawaran' => (float) $penawaran->harga_penawaran,
                'estimasi_hari'  => (int) $penawaran->estimasi_hari,
                'status'         => $penawaran->status,
                'selected_at'    => $penawaran->selected_at?->format('d M Y H:i'),
                'freelancer_id'  => (int) $penawaran->freelancer_id,
            ],
            'is_locked' => $isLocked,
            'deal_summary' => $isLocked ? [
                'status'         => $penawaran->status,
                'is_winner'      => $penawaran->status === 'Diterima',
                'harga_deal'     => (float) $penawaran->harga_penawaran,
                'estimasi_hari'  => (int) $penawaran->estimasi_hari,
                'selected_at'    => $penawaran->selected_at?->format('d M Y H:i'),
                'freelancer_name'=> $penawaran->freelancer?->name ?? 'Freelancer',
                'project_name'   => $project?->project_name ?? 'Proyek',
                'workspace_id'   => $workspace?->id,
                'workspace_url'  => $workspaceUrl,
            ] : null,
            'workspace_url' => $workspaceUrl,
            // Selalu 0: percakapan baru saja ditandai seluruhnya terbaca.
            'unread_negotiation_count' => 0,
        ]);
    }

    /**
     * Kirim pesan negosiasi baru.
     *
     * Khusus Perusahaan: dapat menyertakan tawaran baru (proposed_price &
     * proposed_days) yang tercatat sebagai tawaran aktif berstatus 'pending'
     * untuk disetujui/ditolak oleh freelancer.
     *
     * ATURAN SETELAH DEAL: Jika negosiasi sudah terkunci (freelancer sudah
     * dipilih / workspace sudah ada / status != Menunggu) maka pengiriman
     * ditolak dengan 423 Locked. Riwayat tetap bisa dibaca via getMessages.
     */
    public function sendMessage(Request $request, Penawaran $penawaran): JsonResponse
    {
        $this->authorizeAccess($penawaran);

        if ($this->isNegotiationLocked($penawaran)) {
            return response()->json([
                'success' => false,
                'message' => 'Negosiasi sudah selesai / dikunci. Freelancer telah dipilih. Lanjutkan komunikasi di Workspace.',
                'is_locked' => true,
            ], 423);
        }

        $request->validate([
            'message'        => ['required', 'string', 'max:5000'],
            'proposed_price' => ['nullable', 'numeric', 'min:0'],
            'proposed_days'  => ['nullable', 'integer', 'min:1', 'max:3650'],
        ], [
            'message.required'        => 'Pesan wajib diisi.',
            'proposed_price.numeric'  => 'Harga penawaran baru harus berupa angka.',
            'proposed_days.integer'   => 'Estimasi pengerjaan harus berupa angka hari.',
            'proposed_days.min'       => 'Estimasi pengerjaan minimal 1 hari.',
        ]);

        $user = Auth::user();
        $senderType = ($user->role === 'company') ? 'company' : 'freelancer';

        // Hanya perusahaan yang boleh mengajukan tawaran harga/deadline baru.
        $canPropose = $senderType === 'company';

        $message = NegotiationMessage::create([
            'penawaran_id'   => $penawaran->id,
            'sender_id'      => $user->id,
            'sender_type'    => $senderType,
            'message'        => $request->input('message'),
            'proposed_price' => $canPropose && $request->filled('proposed_price')
                ? $request->input('proposed_price')
                : null,
            'proposed_days'  => $canPropose && $request->filled('proposed_days')
                ? $request->input('proposed_days')
                : null,
            'status'         => NegotiationMessage::STATUS_PENDING,
        ]);

        $this->notifyCounterparty($penawaran, $user, $senderType);

        return response()->json([
            'success' => true,
            'message' => $this->serialize($message),
        ], 201);
    }

    /**
     * Freelancer menyetujui tawaran negosiasi dari perusahaan.
     * Harga & durasi pada penawaran utama diperbarui sesuai tawaran.
     * Ditolak jika negosiasi sudah dikunci (deal).
     */
    public function acceptNegotiation(Penawaran $penawaran, NegotiationMessage $negotiation): JsonResponse
    {
        if ($this->isNegotiationLocked($penawaran)) {
            return response()->json([
                'success' => false,
                'message' => 'Negosiasi sudah dikunci. Tidak dapat menerima tawaran lagi.',
                'is_locked' => true,
            ], 423);
        }
        $this->authorizeFreelancer($penawaran);
        $this->authorizeOffer($penawaran, $negotiation);

        $negotiation->update(['status' => NegotiationMessage::STATUS_ACCEPTED]);

        // Perbarui penawaran utama (proposal/application) sesuai tawaran disetujui.
        $penawaran->update([
            'harga_penawaran' => $negotiation->proposed_price ?? $penawaran->harga_penawaran,
            'estimasi_hari'   => $negotiation->proposed_days ?? $penawaran->estimasi_hari,
        ]);

        return response()->json([
            'success' => true,
            'message' => $this->serialize($negotiation),
            'penawaran' => [
                'id'             => $penawaran->id,
                'harga_penawaran' => (float) $penawaran->harga_penawaran,
                'estimasi_hari'  => (int) $penawaran->estimasi_hari,
            ],
        ]);
    }

    /**
     * Freelancer menolak tawaran negosiasi dari perusahaan.
     * Tidak memerlukan alasan — langsung ubah status menjadi 'rejected'.
     * Ditolak jika negosiasi sudah dikunci.
     */
    public function rejectNegotiation(Penawaran $penawaran, NegotiationMessage $negotiation): JsonResponse
    {
        if ($this->isNegotiationLocked($penawaran)) {
            return response()->json([
                'success' => false,
                'message' => 'Negosiasi sudah dikunci. Tidak dapat menolak tawaran lagi.',
                'is_locked' => true,
            ], 423);
        }
        $this->authorizeFreelancer($penawaran);
        $this->authorizeOffer($penawaran, $negotiation);

        $negotiation->update(['status' => NegotiationMessage::STATUS_REJECTED]);

        return response()->json([
            'success' => true,
            'message' => $this->serialize($negotiation),
        ]);
    }

    /**
     * Kirim notifikasi ke pihak LAWAN pengirim pesan negosiasi.
     *
     * Company mengirim  → recipient = freelancer pemilik penawaran.
     * Freelancer mengirim → recipient = company pemilik proyek.
     */
    private function notifyCounterparty(Penawaran $penawaran, User $user, string $senderType): void
    {
        $project = $penawaran->project;

        $recipientId = $senderType === 'company'
            ? (int) $penawaran->freelancer_id
            : (int) ($project?->user_id ?? 0);

        // Jangan kirim notifikasi ke diri sendiri / jika penerima tidak valid.
        if ($recipientId <= 0 || $recipientId === (int) $user->id) {
            return;
        }

        NotificationService::sendTo(
            user: $recipientId,
            type: 'negotiation.message',
            title: 'Pesan Negosiasi Baru',
            message: $user->name . ' mengirim pesan negosiasi untuk proyek "' . ($project?->project_name ?? '-') . '".',
            redirect: $senderType === 'company'
                ? route('freelancer.lamaran')
                : route('company.projects.show', $project),
            senderId: $user->id,
            penawaranId: $penawaran->id,
            projectId: $project?->id,
        );
    }

    /**
     * Serialisasi satu pesan untuk ditampilkan di frontend.
     */
    private function serialize(NegotiationMessage $msg): array
    {
        return [
            'id'            => $msg->id,
            'message'       => $msg->message,
            'sender_type'   => $msg->sender_type,
            'sender_name'   => $msg->sender?->name ?? ($msg->sender_type === 'company' ? 'Perusahaan' : 'Freelancer'),
            'proposed_price' => $msg->proposed_price !== null ? (float) $msg->proposed_price : null,
            'proposed_days'  => $msg->proposed_days !== null ? (int) $msg->proposed_days : null,
            'status'         => $msg->status,
            'is_mine'       => (int) $msg->sender_id === (int) Auth::id(),
            'created_at'    => $msg->created_at?->format('d M Y H:i'),
        ];
    }

    /**
     * Hanya pihak terkait (pemilik proyek / freelancer pelamar)
     * yang boleh mengakses percakapan penawaran ini.
     */
    private function authorizeAccess(Penawaran $penawaran): void
    {
        $isProjectOwner = $penawaran->project && (int) $penawaran->project->user_id === (int) Auth::id();
        $isFreelancer   = (int) $penawaran->freelancer_id === (int) Auth::id();

        abort_unless($isProjectOwner || $isFreelancer, 403, 'Anda tidak berhak mengakses percakapan ini.');
    }

    /**
     * Hanya freelancer pemilik penawaran yang boleh menyetujui/menolak tawaran.
     */
    private function authorizeFreelancer(Penawaran $penawaran): void
    {
        abort_unless((int) $penawaran->freelancer_id === (int) Auth::id(), 403, 'Hanya freelancer pelamar yang dapat merespons tawaran.');
    }

    /**
     * Pastikan pesan tawaran milik penawaran ini, berasal dari perusahaan,
     * dan masih berstatus pending.
     */
    private function authorizeOffer(Penawaran $penawaran, NegotiationMessage $negotiation): void
    {
        abort_unless((int) $negotiation->penawaran_id === (int) $penawaran->id, 422, 'Tawaran tidak cocok dengan penawaran ini.');
        abort_unless($negotiation->sender_type === 'company', 422, 'Tawaran hanya dapat dibuat oleh perusahaan.');
        abort_unless($negotiation->status === NegotiationMessage::STATUS_PENDING, 422, 'Tawaran ini sudah diproses sebelumnya.');
    }

    /**
     * Apakah negosiasi untuk penawaran ini sudah dikunci?
     * True jika penawaran bukan Menunggu atau project sudah punya workspace.
     */
    private function isNegotiationLocked(Penawaran $penawaran): bool
    {
        if ($penawaran->status !== 'Menunggu') {
            return true;
        }
        $project = $penawaran->project;
        if ($project) {
            // Cek workspace via query agar selalu akurat tanpa perlu eager load
            return \App\Models\Workspace::where('project_id', $project->id)->exists();
        }
        return false;
    }
}