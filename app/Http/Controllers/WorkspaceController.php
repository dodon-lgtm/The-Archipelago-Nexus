<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Message;
use App\Models\Notification;
use App\Models\ProgressHistory;
use App\Services\NotificationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Daftar workspace (freelancer), lengkap dengan status red-dot per workspace
     * berdasarkan notifikasi unread milik user saat ini.
     */
    public function freelancerIndex(): View
    {
        $workspaces = Workspace::with([
            'project',
            'company',
            'latestProgress',
        ])
            ->where('freelancer_id', Auth::id())
            ->latest()
            ->paginate(10);

        $unreadByWorkspace = $this->unreadCountForUser($workspaces, Auth::id());

        return view('workspace.freelancer-index', compact('workspaces', 'unreadByWorkspace'));
    }

    /**
     * Daftar workspace (company), lengkap dengan status red-dot per workspace
     * berdasarkan notifikasi unread milik user saat ini.
     */
    public function companyIndex(): View
    {
        $workspaces = Workspace::with([
            'project',
            'freelancer',
            'latestProgress',
        ])
            ->where('company_id', Auth::id())
            ->latest()
            ->paginate(10);

        $unreadByWorkspace = $this->unreadCountForUser($workspaces, Auth::id());

        return view('workspace.company-index', compact('workspaces', 'unreadByWorkspace'));
    }

    /**
     * Hitung jumlah notifikasi unread per workspace untuk user tertentu.
     * Dipetakan array: [workspace_id => jumlah]. Tidak memicu N+1.
     */
private function unreadCountForUser(LengthAwarePaginator $workspaces, int $userId): array
    {
        $ids = collect($workspaces->items())->pluck('id')->filter()->values()->all();
        if (empty($ids)) {
            return [];
        }

        return Notification::query()
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->whereIn('workspace_id', $ids)
            ->selectRaw('workspace_id, COUNT(*) as total')
            ->groupBy('workspace_id')
            ->pluck('total', 'workspace_id')
            ->map(fn ($v) => (int) $v)
            ->all();
    }

    /**
     * Menampilkan detail workspace (chat + progress).
     */
    public function show(Workspace $workspace): View
    {
        $this->authorizeAccess($workspace);

        // Tandai notifikasi unread terkait workspace ini sebagai read,
        // hanya ketika user BENAR-BENAR membuka halaman detail workspace.
        Notification::where('user_id', Auth::id())
            ->where('workspace_id', $workspace->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $workspace->load([
            'project',
            'company',
            'freelancer',
            'messages' => function ($q) {
                $q->with('sender')->oldest();
            },
            'progressHistories' => function ($q) {
                $q->latest();
            },
            'latestProgress',
            'submissions' => function ($q) {
                $q->with(['submitter', 'files'])->latest();
            },
        ]);

        // ── Stage-based progress ─────────────────────────────────────────────
        // Sumber kebenaran: daftar stage custom terurut milik freelancer.
        $stages = $workspace->stageList();

        // Item tahap lengkap (nama, deskripsi, pembuat) untuk ditampilkan di UI,
        // dienrich dengan objek User pembuat (company/freelancer workspace ini).
        $stageItems = $workspace->stageItems();
        $company = $workspace->company;
        $freelancer = $workspace->freelancer;
        foreach ($stageItems as $i => $item) {
            $stageItems[$i]['creator'] = match ((int) ($item['created_by'] ?? 0)) {
                (int) ($freelancer->id ?? 0) => $freelancer,
                (int) ($company->id ?? 0) => $company,
                default => null,
            };
        }

        $latestProgress = $workspace->latestProgress;
        $activeStage = $latestProgress?->stage;
        $activeStageOrder = ($latestProgress && $latestProgress->stage_order)
            ? (int) $latestProgress->stage_order
            : (!$activeStage ? 0 : (array_search($activeStage, $stages) !== false ? array_search($activeStage, $stages) + 1 : 0));

        $totalStages = count($stages);

        // Persentase dihitung SERVER-SIDE dari urutan stage, bukan dari browser.
        $progressValue = ($latestProgress && $activeStageOrder > 0)
            ? $workspace->calculateProgressForStage($activeStageOrder)
            : 0;

        // Load payment data if exists
        $payment = $workspace->payment;

        return view('workspace.show', compact(
            'workspace', 'stages', 'stageItems', 'activeStage',
            'activeStageOrder', 'totalStages',
            'latestProgress', 'progressValue',
            'payment'
        ));
    }

    /**
     * Kirim pesan chat.
     */
    public function sendMessage(Request $request, Workspace $workspace): RedirectResponse
    {
        $this->authorizeAccess($workspace);

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'type' => 'user',
        ]);

        // Tentukan penerima notifikasi (lawan bicara)
        $receiverId = Auth::id() === (int) $workspace->company_id
            ? $workspace->freelancer_id
            : $workspace->company_id;

        // Tentukan redirect sesuai role penerima
        $receiverRole = ($receiverId === (int) $workspace->company_id) ? 'company' : 'freelancer';
        $redirectRoute = $receiverRole === 'company'
            ? route('company.workspaces.show', $workspace)
            : route('freelancer.workspaces.show', $workspace);

        // Notifikasi ke lawan bicara: pesan baru
        NotificationService::sendTo(
            user: $receiverId,
            type: 'workspace.message',
            title: 'Pesan Baru',
            message: 'Anda menerima pesan baru pada workspace proyek "' . ($workspace->project->project_name ?? '') . '".',
            redirect: $redirectRoute,
            senderId: Auth::id(),
            workspaceId: $workspace->id,
            projectId: $workspace->project_id,
        );

        return redirect()
            ->route(
                Auth::user()->role === 'company' ? 'company.workspaces.show' : 'freelancer.workspaces.show',
                $workspace
            )
            ->with('success', 'Pesan berhasil dikirim.');
    }

    /**
     * Update progress berbasis STAGE (hanya freelancer).
     *
     * Prinsip keamanan:
     * - Persentase SELALU dihitung server-side dari urutan stage.
     * - Nilai `progress`/`percentage`/`completion` dari browser DIBIARKAN.
     * - Freelancer hanya mengontrol stage (nama + urutan).
     */
    public function updateProgress(Request $request, Workspace $workspace): RedirectResponse
    {
        // Pelaku harus BENAR-BENAR terkait dengan workspace ini:
        // Company pemilik ATAU freelancer yang ditugaskan.
        $this->authorizeAccess($workspace);

        $action = (string) $request->input('action', '');

        // Aksi yang mengubah status progres (select/move_next) TETAP khusus freelancer.
        if (in_array($action, ['select', 'move_next'], true)
            && (int) $workspace->freelancer_id !== (int) Auth::id()) {
            abort(403, 'Hanya freelancer yang dapat mengupdate progress.');
        }

        // BACKEND GUARD: Kunci perubahan tahap jika workspace dalam tahap pembayaran atau selesai
        if (in_array($workspace->status, ['Menunggu Pembayaran', 'Menunggu Verifikasi Admin', 'Selesai'], true)) {
            return redirect()
                ->route($this->backToWorkspace(), $workspace)
                ->with('error', 'Tidak dapat mengubah tahap/progres selama workspace dalam proses pembayaran, verifikasi admin, atau sudah selesai.');
        }

        $request->validate([
            'action' => 'required|in:select,add,rename,delete,move_next',
            // Untuk "select": pilih stage yang ada (disimpan sebagai nama stage + order).
            // "progress" TIDAK divalidasi sebagai input otoritatif dan TIDAK dipakai.
            'stage' => 'nullable|string|max:255',
            'new_stage' => 'nullable|string|max:255',
            'old_stage' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $stageItems = $workspace->stageItems();
        $stages = array_values(array_map(fn (array $item) => $item['name'], $stageItems));
        $description = $request->input('description');
        $userId = (int) Auth::id();

        $latestProgress = $workspace->latestProgress()->first();

        // ── Mutasi daftar stage (source of truth) berdasarkan aksi ──────────
        switch ($action) {
            case 'add':
                $newStage = trim((string) $request->input('new_stage', ''));
                if ($newStage === '') {
                    return $this->backWithError('Nama tahap tidak boleh kosong.');
                }
                if (in_array($newStage, $stages, true)) {
                    return $this->backWithError('Tahap "' . $newStage . '" sudah ada.');
                }
                // Sumber kebenaran satu-satu: simpan pembuat + deskripsi.
                // Urutan baru = posisi terakhir + 1 (= append).
                $stageItems[] = [
                    'name' => $newStage,
                    'description' => $description !== null && $description !== '' ? (string) $description : null,
                    'created_by' => $userId,
                ];
                $workspace->update(['stages' => $stageItems]);
                return $this->backWithSuccess('Tahap "' . $newStage . '" berhasil ditambahkan.');

            case 'rename':
                $oldStage = trim((string) $request->input('old_stage', ''));
                $newStage = trim((string) $request->input('new_stage', ''));
                if ($oldStage === '' || $newStage === '') {
                    return $this->backWithError('Nama tahap lama dan baru wajib diisi.');
                }
                $pos = $this->findStagePosition($stageItems, $oldStage);
                if ($pos === null) {
                    return $this->backWithError('Tahap "' . $oldStage . '" tidak ditemukan.');
                }
                if (!$this->canMutateStage($workspace, $stageItems[$pos], $userId)) {
                    abort(403, 'Anda hanya dapat mengubah tahap pada project yang Anda kelola.');
                }
                if ($oldStage !== $newStage && in_array($newStage, $stages, true)) {
                    return $this->backWithError('Tahap "' . $newStage . '" sudah ada.');
                }
                // Ganti nama, pertahankan urutan (posisi) + pembuat/deskripsi.
                $stageItems[$pos]['name'] = $newStage;
                $workspace->update(['stages' => array_values($stageItems)]);

                // Sinkronkan stage_order/nama pada riwayat yang masih memakai nama lama.
                ProgressHistory::where('workspace_id', $workspace->id)
                    ->where('stage', $oldStage)
                    ->update(['stage' => $newStage]);

                return $this->backWithSuccess('Nama tahap berhasil diubah menjadi "' . $newStage . '".');

            case 'delete':
                $deleteStage = trim((string) $request->input('old_stage', ''));
                if ($deleteStage === '') {
                    return $this->backWithError('Tahap yang akan dihapus wajib diisi.');
                }
                $pos = $this->findStagePosition($stageItems, $deleteStage);
                if ($pos === null) {
                    return $this->backWithError('Tahap "' . $deleteStage . '" tidak ditemukan.');
                }
                if (!$this->canMutateStage($workspace, $stageItems[$pos], $userId)) {
                    abort(403, 'Anda hanya dapat menghapus tahap pada project yang Anda kelola.');
                }
                unset($stageItems[$pos]);
                // Re-index otomatis: urutan tahap yang belak naik 1 (pekerjaan lama aman).
                $workspace->update(['stages' => array_values($stageItems)]);
                return $this->backWithSuccess('Tahap "' . $deleteStage . '" berhasil dihapus.');

            case 'move_next':
                // Pindah ke stage berikutnya dari stage aktif saat ini.
                $currentOrder = $latestProgress?->stage_order
                    ? (int) $latestProgress->stage_order
                    : 0;

                if ($currentOrder >= count($stages)) {
                    return $this->backWithError('Anda sudah berada di tahap terakhir.');
                }

                $nextOrder = $currentOrder + 1;
                $nextStage = $stages[$nextOrder - 1];
                $progress = $workspace->calculateProgressForStage($nextOrder);

                ProgressHistory::create([
                    'workspace_id' => $workspace->id,
                    'stage' => $nextStage,
                    'stage_order' => $nextOrder,
                    'progress' => $progress,
                    'description' => $description,
                    'updated_by' => $userId,
                ]);

                $this->handleCompletion($workspace, $progress);
                return $this->backWithSuccess('Progres bergerak ke tahap "' . $nextStage . '" (' . $progress . '%).');

            case 'select':
            default:
                // Freelancer memilih salah satu stage yang sudah ada.
                $stage = trim((string) $request->input('stage', ''));
                $order = array_search($stage, $stages, true);
                if ($order === false) {
                    return $this->backWithError('Tahap yang dipilih tidak valid.');
                }

                $selectedOrder = $order + 1; // 1-based
                // Persentase dihitung server-side; nilai `progress` dari browser TIDAK dipakai.
                $progress = $workspace->calculateProgressForStage($selectedOrder);

                ProgressHistory::create([
                    'workspace_id' => $workspace->id,
                    'stage' => $stage,
                    'stage_order' => $selectedOrder,
                    'progress' => $progress,
                    'description' => $description,
                    'updated_by' => $userId,
                ]);

                $this->handleCompletion($workspace, $progress);
                return $this->backWithSuccess('Progres diperbarui ke tahap "' . $stage . '" (' . $progress . '%).');
        }
    }

    /**
     * Bila progres mencapai 100% (stage terakhir), alihkan status ke "Menunggu Review"
     * dan kirim pesan sistem. Menunggu Company memeriksa hasil pekerjaan.
     * Konfirmasi akhir tetap oleh perusahaan (tidak berubah).
     */
    private function handleCompletion(Workspace $workspace, int $progress): void
    {
        $restrictedStatuses = ['Selesai', 'Menunggu Pembayaran', 'Menunggu Verifikasi Admin'];

        if ($progress >= 100 && !in_array($workspace->status, $restrictedStatuses, true)) {
            $workspace->update(['status' => 'Menunggu Review']);

            Message::create([
                'workspace_id' => $workspace->id,
                'sender_id' => Auth::id(),
                'message' => 'Freelancer telah menyelesaikan pekerjaan dan menunggu review perusahaan.',
                'type' => 'system',
            ]);

            // Notifikasi ke company: pekerjaan menunggu review
            NotificationService::sendTo(
                user: (int) $workspace->company_id,
                type: 'workspace.awaiting_review',
                title: 'Menunggu Review',
                message: 'Freelancer telah menyelesaikan pekerjaan untuk proyek "' . ($workspace->project->project_name ?? '') . '". Silakan lakukan review.',
                redirect: route('company.workspaces.show', $workspace),
                senderId: Auth::id(),
                workspaceId: $workspace->id,
                projectId: $workspace->project_id,
            );
        }
    }

    /**
     * Nama route detail workspace sesuai role user yang sedang login.
     */
    private function backToWorkspace(): string
    {
        return Auth::user()->role === 'company'
            ? 'company.workspaces.show'
            : 'freelancer.workspaces.show';
    }

    /**
     * Redirect balik dengan pesan sukses.
     */
    private function backWithSuccess(string $message): RedirectResponse
    {
        return redirect()
            ->route($this->backToWorkspace(), request()->route('workspace'))
            ->with('success', $message);
    }

    /**
     * Redirect balik dengan pesan error.
     */
    private function backWithError(string $message): RedirectResponse
    {
        return redirect()
            ->route($this->backToWorkspace(), request()->route('workspace'))
            ->with('error', $message);
    }

    /**
     * Cari posisi (index) sebuah tahap berdasarkan nama di daftar stage items.
     */
    private function findStagePosition(array $stageItems, string $name): ?int
    {
        foreach ($stageItems as $i => $item) {
            if (($item['name'] ?? null) === $name) {
                return $i;
            }
        }

        return null;
    }

    /**
     * Apakah user yang login adalah pembuat tahap ini?
     */
    private function userOwnsStage(array $stageItem, int $userId): bool
    {
        $creator = $stageItem['created_by'] ?? null;

        return $creator !== null && (int) $creator === $userId;
    }

    /**
     * Otorisasi mutasi tahap pada workspace ini (REVISI Tahap Pengerjaan).
     *
     * Kebijakan (keep existing behavior untuk freelancer):
     * - Company PEMILIK workspace (company_id === user) boleh mengubah/menghapus
     *   SEMUA tahap pada project miliknya (full CRUD atas workflow project).
     *   Akses lintas-company tetap diblokir oleh `authorizeAccess()`.
     * - Freelancer hanya boleh mengubah/menghapus tahap yang ia buat sendiri
     *   (perilaku lama dipertahankan).
     */
    private function canMutateStage(Workspace $workspace, array $stageItem, int $userId): bool
    {
        $isCompanyOwner = (int) $workspace->company_id === $userId;

        if ($isCompanyOwner) {
            return true;
        }

        return $this->userOwnsStage($stageItem, $userId);
    }

    /**
     * Otorisasi akses workspace.
     */
    private function authorizeAccess(Workspace $workspace): void
    {
        $user = Auth::user();
        $isCompany = (int) $workspace->company_id === (int) $user->id;
        $isFreelancer = (int) $workspace->freelancer_id === (int) $user->id;

        if (!$isCompany && !$isFreelancer) {
            abort(403, 'Anda tidak memiliki akses ke workspace ini.');
        }
    }
}

