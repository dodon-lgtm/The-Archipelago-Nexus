<?php

namespace App\Http\Controllers;

use App\Models\NegotiationMessage;
use App\Models\Penawaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NegotiationController extends Controller
{
    /**
     * Ambil seluruh riwayat chat negosiasi untuk sebuah penawaran.
     */
    public function getMessages(Penawaran $penawaran): JsonResponse
    {
        $this->authorizeAccess($penawaran);

        $messages = NegotiationMessage::with('sender:id,name')
            ->where('penawaran_id', $penawaran->id)
            ->orderBy('created_at')
            ->get()
            ->map(function (NegotiationMessage $msg) {
                return $this->serialize($msg);
            });

        return response()->json([
            'success'  => true,
            'messages' => $messages,
            'penawaran' => [
                'id'             => $penawaran->id,
                'harga_penawaran' => (float) $penawaran->harga_penawaran,
                'estimasi_hari'  => (int) $penawaran->estimasi_hari,
                'status'         => $penawaran->status,
            ],
        ]);
    }

    /**
     * Kirim pesan negosiasi baru.
     *
     * Khusus Perusahaan: dapat menyertakan tawaran baru (proposed_price &
     * proposed_days) yang tercatat sebagai tawaran aktif berstatus 'pending'
     * untuk disetujui/ditolak oleh freelancer.
     */
    public function sendMessage(Request $request, Penawaran $penawaran): JsonResponse
    {
        $this->authorizeAccess($penawaran);

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

        return response()->json([
            'success' => true,
            'message' => $this->serialize($message),
        ], 201);
    }

    /**
     * Freelancer menyetujui tawaran negosiasi dari perusahaan.
     * Harga & durasi pada penawaran utama diperbarui sesuai tawaran.
     */
    public function acceptNegotiation(Penawaran $penawaran, NegotiationMessage $negotiation): JsonResponse
    {
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
     */
    public function rejectNegotiation(Penawaran $penawaran, NegotiationMessage $negotiation): JsonResponse
    {
        $this->authorizeFreelancer($penawaran);
        $this->authorizeOffer($penawaran, $negotiation);

        $negotiation->update(['status' => NegotiationMessage::STATUS_REJECTED]);

        return response()->json([
            'success' => true,
            'message' => $this->serialize($negotiation),
        ]);
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
}