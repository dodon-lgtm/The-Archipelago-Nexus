<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\Message;
use App\Models\Notification;
use App\Models\ProgressHistory;
use App\Services\NotificationService;
use App\Services\OverdueWorkspaceService;
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
     * berdasarkan notifikasi unread milik user saat ini & FILTER pencarian/status.
     */
    public function freelancerIndex(Request $request): View
    {
        // Proses pengecekan deadline saat halaman dibuka/di-refresh, tanpa
        // bergantung pada scheduler / `php artisan schedule:work`.
        OverdueWorkspaceService::process();

        $search = trim((string) $request->query('search', ''));
        $statusFilter = $request->query('status');

        $query = Workspace::with([
            'project',
            'company',
            'latestProgress',
        ])
            ->where('freelancer_id', Auth::id());

        // Filter berdasarkan status
        if ($statusFilter && $statusFilter !== 'all' && $statusFilter !== '') {
            $query->where('status', $statusFilter);
        }

        // Filter berdasarkan kata kunci pencarian (nama proyek / nama perusahaan)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('project', function ($p) use ($search) {
                    $p->where('project_name', 'like', "%{$search}%");
                })
                ->orWhereHas('company', function ($c) use ($search) {
                    $c->where('name', 'like', "%{$search}%");
                });
            });
        }

        $workspaces = $query->latest()->paginate(10)->withQueryString();

        $unreadByWorkspace = $this->unreadCountForUser($workspaces, Auth::id());

        return view('workspace.freelancer-index', compact('workspaces', 'unreadByWorkspace'));
    }

    /**
     * Daftar workspace (company), lengkap dengan status red-dot per workspace
     * berdasarkan notifikasi unread milik user saat ini.
     * + FILTER PROJECT OTOMATIS: chip diambil dari project yang sudah memiliki Workspace milik company login.
     */
    public function companyIndex(Request $request): View
    {
        // Proses pengecekan deadline saat halaman dibuka/di-refresh, tanpa
        // bergantung pada scheduler / `php artisan schedule:work`.
        OverdueWorkspaceService::process();

        $projectFilter = $request->query('project');
        $search = trim((string) $request->query('search', ''));
        $statusFilter = $request->query('status');

        // Sumber filter: distinct project_id dari workspace milik company ini saja
        $projectIds = Workspace::where('company_id', Auth::id())
            ->distinct()
            ->pluck('project_id')
            ->filter()
            ->values();

        $filterProjects = collect();
        if ($projectIds->isNotEmpty()) {
            $filterProjects = \App\Models\Project::whereIn('id', $projectIds)
                ->where('user_id', Auth::id())
                ->orderBy('project_name')
                ->get(['id', 'project_name']);
        }

        // Validasi filter: hanya izinkan project milik company yang memang ada di filterProjects
        $validIds = $filterProjects->pluck('id')->map(fn($v) => (string) $v)->all();
        if ($projectFilter && $projectFilter !== 'all' && $projectFilter !== '' && !in_array((string) $projectFilter, $validIds, true)) {
            $projectFilter = 'all';
        }

        $validStatuses = ['Sedang Dikerjakan','Menunggu Review','Menunggu Revisi','Menunggu Pembayaran','Menunggu Verifikasi Admin','Selesai','Melewati Batas Waktu'];
        if ($statusFilter && $statusFilter !== 'all' && $statusFilter !== '' && !in_array($statusFilter, $validStatuses, true)) {
            $statusFilter = 'all';
        }

        $query = Workspace::with([
            'project',
            'freelancer',
            'latestProgress',
        ])
            ->where('company_id', Auth::id());

        if ($projectFilter && $projectFilter !== 'all' && $projectFilter !== '') {
            $query->where('project_id', $projectFilter);
        }

        if ($statusFilter && $statusFilter !== 'all' && $statusFilter !== '') {
            $query->where('status', $statusFilter);
        }

        if ($search !== '') {
            $query->whereHas('project', function($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%");
            });
        }

        $workspaces = $query->latest()->paginate(10)->withQueryString();

        $unreadByWorkspace = $this->unreadCountForUser($workspaces, Auth::id());

        $activeProject = ($projectFilter && $projectFilter !== '' ) ? (string) $projectFilter : 'all';
        $activeStatus = ($statusFilter && $statusFilter !== '' ) ? (string) $statusFilter : 'all';

        return view('workspace.company-index', compact('workspaces', 'unreadByWorkspace', 'filterProjects', 'activeProject', 'activeStatus', 'search'));
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

        // Tandai semua pesan chat dalam room ini yang BUKAN dikirim oleh
        // user yang sedang login sebagai terbaca, sehingga indikator
        // "Pesan Baru" di dashboard freelancer ter-update secara akurat.
        Message::markAsReadForUser((int) $workspace->id, (int) Auth::id());

        // Proses pengecekan deadline saat halaman dibuka/di-refresh, tanpa
        // bergantung pada scheduler / `php artisan schedule:work`. Diletakkan
        // SETELAH penandaan notifikasi read agar notifikasi overdue yang baru
        // dibuat tidak ikut tertandai read (perilaku sama seperti command
        // `workspaces:mark-overdue`), lalu segarkan data workspace supaya
        // status yang dirender selalu mutakhir.
        OverdueWorkspaceService::process();
        $workspace->refresh();

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

        // Persentase dihitung SERVER-SIDE dari JUMLAH CEKLIS (count-based),
        // bukan dari urutan stage dan bukan dari nilai browser.
        $progressValue = $workspace->currentProgress();
        $completedCountVal = $workspace->completedStageCount();

        // Load payment data if exists
        $payment = $workspace->payment;

        return view('workspace.show', compact(
            'workspace', 'stages', 'stageItems', 'activeStage',
            'activeStageOrder', 'totalStages',
            'latestProgress', 'progressValue', 'completedCountVal',
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
            'action' => 'required|in:select,note,add,rename,delete,move_next',
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

        // Catatan: properti `latestProgress` dulu dipakai untuk perhitungan progres
        // berbasis urutan; kini seluruh perhitungan progres murni berbasis
        // JUMLAH CEKLIS (count-based) via Workspace::currentProgress().

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
                // Urutan baru = posisi terakhir + 1 (= append). Status ceklis
                // default false (bukan selesai) sampai freelancer menandainya.
                $stageItems[] = [
                    'name' => $newStage,
                    'description' => $description !== null && $description !== '' ? (string) $description : null,
                    'created_by' => $userId,
                    'is_completed' => false,
                ];
                $workspace->update(['stages' => $stageItems]);
                $this->syncProgressColumn($workspace);
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
                // Ganti nama + deskripsi (dari form Edit tahap), pertahankan urutan (posisi) + pembuat.
                $stageItems[$pos]['name'] = $newStage;
                $stageItems[$pos]['description'] = $description !== null && $description !== ''
                    ? (string) $description
                    : null;
                $workspace->update(['stages' => array_values($stageItems)]);
                $this->syncProgressColumn($workspace);

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
                $this->syncProgressColumn($workspace);
                return $this->backWithSuccess('Tahap "' . $deleteStage . '" berhasil dihapus.');

            case 'move_next':
                // Tandai tahap berikutnya (yang belum dicentang) sebagai SELESAI.
                // Progress dihitung murni dari JUMLAH CEKLIS (count-based),
                // bukan dari nomor urut tahap.
                $nextPos = null;
                foreach ($stageItems as $i => $item) {
                    if (empty($item['is_completed'])) {
                        $nextPos = $i;
                        break;
                    }
                }

                if ($nextPos === null) {
                    return $this->backWithError('Semua tahap sudah dinyatakan selesai.');
                }

                $nextOrder = $nextPos + 1; // 1-based
                $nextStage = $stages[$nextPos];
                $stageItems[$nextPos]['is_completed'] = true;
                $workspace->update(['stages' => $stageItems]);
                $this->syncProgressColumn($workspace);
                $progress = $workspace->currentProgress();

                ProgressHistory::create([
                    'workspace_id' => $workspace->id,
                    'stage' => $nextStage,
                    'stage_order' => $nextOrder,
                    'progress' => $progress,
                    'description' => $description,
                    'updated_by' => $userId,
                ]);

                $this->handleCompletion($workspace, $progress);
                return $this->backWithSuccess('Tahap "' . $nextStage . '" ditandai selesai (' . $progress . '%).');

            case 'note':
                // Simpan/ubah CATATAN pengerjaan tahap TANPA mengubah status
                // is_completed. Berbeda dengan `select` yang men-toggle ceklis.
                $stage = trim((string) $request->input('stage', ''));
                $order = array_search($stage, $stages, true);
                if ($order === false) {
                    return $this->backWithError('Tahap yang dipilih tidak valid.');
                }

                ProgressHistory::create([
                    'workspace_id' => $workspace->id,
                    'stage' => $stage,
                    'stage_order' => $order + 1, // 1-based
                    'progress' => $workspace->currentProgress(),
                    'description' => $description,
                    'updated_by' => $userId,
                ]);

                return $this->backWithSuccess('Catatan tahap \\"' . $stage . '\\" berhasil diperbarui.');

                        case 'select':
            default:
                // Freelancer memilih salah satu stage yang sudah ada.
                // Aksi = TOGGLE ceklis: HANYA status tahap yang dipilih yang
                // diubah (is_completed), tahap lain TIDAK disentuh sama sekali.
                $stage = trim((string) $request->input('stage', ''));
                $order = array_search($stage, $stages, true);
                if ($order === false) {
                    return $this->backWithError('Tahap yang dipilih tidak valid.');
                }

                $selectedOrder = $order + 1; // 1-based
                $currentlyCompleted = !empty($stageItems[$order]['is_completed']);
                $stageItems[$order]['is_completed'] = !$currentlyCompleted;
                // Simpan deskripsi pengerjaan bersamaan dengan perubahan status
                if ($description !== null && $description !== '') {
                    $stageItems[$order]['description'] = $description;
                }
                $workspace->update(['stages' => $stageItems]);
                $this->syncProgressColumn($workspace);

                // Persentase dihitung server-side dari JUMLAH CEKLIS (count-based);
                // nilai `progress` dari browser TIDAK dipakai.
                $progress = $workspace->currentProgress();
                $verb = $currentlyCompleted ? 'dibatalkan (belum selesai)' : 'ditandai selesai';

                ProgressHistory::create([
                    'workspace_id' => $workspace->id,
                    'stage' => $stage,
                    'stage_order' => $selectedOrder,
                    'progress' => $progress,
                    'description' => $description,
                    'updated_by' => $userId,
                ]);

                $this->handleCompletion($workspace, $progress);
                return $this->backWithSuccess('Tahap "' . $stage . '" ' . $verb . ' (' . $progress . '%).');
        }
    }

    /**
     * Sinkronkan kolom `progress` di tabel project_workspaces dengan nilai
     * count-based terbaru: COUNT(tahap_selesai) / COUNT(semua_tahap) * 100.
     * Dipanggil setiap kali daftar tahap atau status ceklis berubah.
     */
    private function syncProgressColumn(Workspace $workspace): void
    {
        $workspace->update(['progress' => $workspace->currentProgress()]);
    }

    /**
     * Bila progres mencapai 100% (SEMUA tahap dicentang selesai), alihkan
     * status ke "Menunggu Review" dan kirim pesan sistem. Menunggu Company
     * memeriksa hasil pekerjaan.
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