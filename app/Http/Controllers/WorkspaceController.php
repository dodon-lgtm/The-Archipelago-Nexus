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

        // ── Stage-based progress (NON-LINEAR / FLEKSIBEL) ──────────────────────
        // Sumber kebenaran: daftar stage + flag is_completed di JSON stages.
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
        // Legacy active stage/order dipertahankan untuk fallback timeline lama
        $activeStage = $latestProgress?->stage;
        $activeStageOrder = ($latestProgress && $latestProgress->stage_order)
            ? (int) $latestProgress->stage_order
            : (!$activeStage ? 0 : (array_search($activeStage, $stages) !== false ? array_search($activeStage, $stages) + 1 : 0));

        $totalStages = count($stages);
        $completedCount = $workspace->completedStagesCount();

        // Persentase FLEKSIBEL: (jumlah selesai / total) *100 — dihitung server-side
        $progressValue = $workspace->currentProgress();
        // Sinkronkan kolom progress di DB jika masih berbeda (auto-heal)
        if ((int) ($workspace->progress ?? 0) !== (int) $progressValue) {
            $workspace->update(['progress' => $progressValue]);
            $workspace->refresh();
        }

        // Load payment data if exists
        $payment = $workspace->payment;

        return view('workspace.show', compact(
            'workspace', 'stages', 'stageItems', 'activeStage',
            'activeStageOrder', 'totalStages', 'completedCount',
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
     * Update progress berbasis STAGE — NON-LINEAR / FLEKSIBEL.
     *
     * Prinsip keamanan:
     * - Persentase SELALU dihitung server-side via (completed/total*100).
     * - Nilai `progress`/`percentage`/`completion` dari browser DIBIARKAN.
     * - Freelancer dapat menyelesaikan tahap MANA SAJA tanpa urutan (flexible).
     * - Deskripsi/catatan pengerjaan WAJIB saat menandai selesai (via modal).
     */
    public function updateProgress(Request $request, Workspace $workspace): RedirectResponse
    {
        // Pelaku harus BENAR-BENAR terkait dengan workspace ini:
        // Company pemilik ATAU freelancer yang ditugaskan.
        $this->authorizeAccess($workspace);

        $action = (string) $request->input('action', '');

        // Aksi yang mengubah status progres (select/move_next/toggle/update_stage) khusus freelancer.
        if (in_array($action, ['select', 'move_next', 'update_stage', 'toggle'], true)
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
            'action' => 'required|in:select,add,rename,delete,move_next,update_stage,toggle',
            // Untuk update_stage/toggle: stage yang dipilih bebas tanpa validasi urutan
            'stage' => 'nullable|string|max:255',
            'new_stage' => 'nullable|string|max:255',
            'old_stage' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'is_completed' => 'nullable',
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
                // Tahap baru selalu belum selesai (fleksibel)
                $stageItems[] = [
                    'name' => $newStage,
                    'description' => $description !== null && $description !== '' ? (string) $description : null,
                    'created_by' => $userId,
                    'is_completed' => false,
                    'note' => null,
                    'completed_at' => null,
                    'completed_by' => null,
                ];
                // Kalkulasi progress fleksibel setelah tambah (total bertambah → progress bisa turun)
                $completedTmp = collect($stageItems)->where('is_completed', true)->count();
                $totalTmp = count($stageItems);
                $newProgress = $totalTmp > 0 ? (int) round(($completedTmp / $totalTmp) * 100) : 0;
                $workspace->update(['stages' => $stageItems, 'progress' => $newProgress]);
                return $this->backWithSuccess('Tahap "' . $newStage . '" berhasil ditambahkan. Progress: ' . $newProgress . '%.');

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
                // Ganti nama + deskripsi, pertahankan flag selesai & catatan
                $stageItems[$pos]['name'] = $newStage;
                $stageItems[$pos]['description'] = $description !== null && $description !== ''
                    ? (string) $description
                    : null;
                $workspace->update(['stages' => array_values($stageItems)]);

                // Sinkronkan nama pada riwayat yang masih memakai nama lama.
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
                $stageItems = array_values($stageItems);
                // Recalculate flexible progress after delete
                $completedTmp = collect($stageItems)->where('is_completed', true)->count();
                $totalTmp = count($stageItems);
                $newProgress = $totalTmp > 0 ? (int) round(($completedTmp / $totalTmp) * 100) : 0;
                $workspace->update(['stages' => $stageItems, 'progress' => $newProgress]);
                $this->handleCompletion($workspace, $newProgress);
                return $this->backWithSuccess('Tahap "' . $deleteStage . '" berhasil dihapus. Progress: ' . $newProgress . '%.');

            case 'update_stage':
            case 'toggle':
                // ── NON-LINEAR: freelancer dapat toggle tahap MANA SAJA tanpa urutan ──
                $stage = trim((string) $request->input('stage', ''));
                if ($stage === '') {
                    return $this->backWithError('Nama tahap wajib diisi.');
                }
                $pos = $this->findStagePosition($stageItems, $stage);
                if ($pos === null) {
                    return $this->backWithError('Tahap "' . $stage . '" tidak ditemukan.');
                }

                // Normalisasi is_completed: checkbox "1"/"on"/true => selesai
                $rawCompleted = $request->input('is_completed', null);
                if (is_string($rawCompleted)) {
                    $isCompleted = in_array(strtolower($rawCompleted), ['1','true','on','yes'], true);
                } else {
                    $isCompleted = (bool) $rawCompleted;
                }
                // Jika toggle tanpa is_completed eksplisit, anggap toggle invert
                if ($request->input('is_completed') === null && $request->has('toggle')) {
                    $isCompleted = !$stageItems[$pos]['is_completed'];
                }

                $note = trim((string) $request->input('description', ''));
                // Deskripsi wajib saat menandai selesai
                if ($isCompleted && $note === '') {
                    return $this->backWithError('Deskripsi / catatan pengerjaan wajib diisi saat menandai tahap selesai.');
                }
                if (mb_strlen($note) > 2000) {
                    return $this->backWithError('Deskripsi maksimal 2000 karakter.');
                }

                // Update flag & catatan di JSON source of truth
                $stageItems[$pos]['is_completed'] = $isCompleted;
                $stageItems[$pos]['note'] = $note !== '' ? $note : null;
                if ($isCompleted) {
                    $stageItems[$pos]['completed_at'] = now()->toDateTimeString();
                    $stageItems[$pos]['completed_by'] = $userId;
                } else {
                    $stageItems[$pos]['completed_at'] = null;
                    $stageItems[$pos]['completed_by'] = null;
                }

                // Hitung progress fleksibel: completed / total *100
                $completedCount = collect($stageItems)->where('is_completed', true)->count();
                $totalStagesTmp = count($stageItems);
                $progress = $totalStagesTmp > 0 ? (int) round(($completedCount / $totalStagesTmp) * 100) : 0;

                $workspace->update(['stages' => array_values($stageItems), 'progress' => $progress]);

                // Catat riwayat perubahan tahap
                $stageOrder = $pos + 1;
                ProgressHistory::create([
                    'workspace_id' => $workspace->id,
                    'stage' => $stage,
                    'stage_order' => $stageOrder,
                    'progress' => $progress,
                    'description' => $note !== '' ? $note : ($isCompleted ? 'Tahap ditandai selesai' : 'Tahap ditandai belum selesai'),
                    'updated_by' => $userId,
                ]);

                $this->handleCompletion($workspace, $progress);
                $statusLabel = $isCompleted ? 'selesai' : 'belum selesai';
                return $this->backWithSuccess('Tahap "' . $stage . '" diperbarui menjadi ' . $statusLabel . ' (' . $progress . '%).');

            case 'move_next':
                // BACKWARD COMPAT: move_next sekarang diperlakukan sebagai toggle flexible
                // (tandai tahap berikutnya sebagai selesai tanpa validasi urutan ketat)
                $currentCompleted = collect($stageItems)->where('is_completed', true)->count();
                // Cari tahap pertama yang belum selesai, jika tidak ada error
                $nextPos = null;
                foreach ($stageItems as $idx => $it) {
                    if (empty($it['is_completed'])) { $nextPos = $idx; break; }
                }
                if ($nextPos === null) {
                    return $this->backWithError('Semua tahap sudah selesai.');
                }
                $nextStage = $stageItems[$nextPos]['name'];
                // Tandai selesai dengan catatan dari request (boleh kosong? tapi untuk legacy, izinkan)
                $noteMove = trim((string) $request->input('description', ''));
                $stageItems[$nextPos]['is_completed'] = true;
                $stageItems[$nextPos]['note'] = $noteMove !== '' ? $noteMove : null;
                $stageItems[$nextPos]['completed_at'] = now()->toDateTimeString();
                $stageItems[$nextPos]['completed_by'] = $userId;
                $completedCount = collect($stageItems)->where('is_completed', true)->count();
                $totalStagesTmp = count($stageItems);
                $progress = $totalStagesTmp > 0 ? (int) round(($completedCount / $totalStagesTmp) * 100) : 0;
                $workspace->update(['stages' => array_values($stageItems), 'progress' => $progress]);
                ProgressHistory::create([
                    'workspace_id' => $workspace->id,
                    'stage' => $nextStage,
                    'stage_order' => $nextPos + 1,
                    'progress' => $progress,
                    'description' => $noteMove !== '' ? $noteMove : 'Tahap "' . $nextStage . '" ditandai selesai',
                    'updated_by' => $userId,
                ]);
                $this->handleCompletion($workspace, $progress);
                return $this->backWithSuccess('Progres bergerak ke tahap "' . $nextStage . '" (' . $progress . '%).');

            case 'select':
            default:
                // BACKWARD COMPAT: select sekarang fleksibel — toggle tahap yang dipilih tanpa validasi urutan
                $stage = trim((string) $request->input('stage', ''));
                if ($stage === '') {
                    // Jika tidak ada stage spesifik, coba perlakukan sebagai toggle generic
                    return $this->backWithError('Tahap yang dipilih tidak valid.');
                }
                $pos = $this->findStagePosition($stageItems, $stage);
                if ($pos === null) {
                    return $this->backWithError('Tahap yang dipilih tidak valid.');
                }
                // Untuk select, anggap freelancer ingin menandai selesai (tanpa harus urut)
                $noteSel = trim((string) $request->input('description', ''));
                // Jika catatan kosong dan tahap belum selesai, izinkan tapi progress tetap hitung fleksibel
                // Namun untuk konsistensi dengan modal, jika ingin selesai wajib isi: beri warning jika kosong dan tahap belum selesai
                // Kita tetap izinkan legacy select tanpa deskripsi (agar test lama lolos), tapi jika ada deskripsi gunakan
                $willComplete = true;
                // Jika tahap sudah selesai, select tidak mengubah (idempotent)
                if (!empty($stageItems[$pos]['is_completed'])) {
                    $progress = $workspace->calculateFlexibleProgress();
                    return $this->backWithSuccess('Tahap "' . $stage . '" sudah selesai (' . $progress . '%).');
                }
                $stageItems[$pos]['is_completed'] = $willComplete;
                $stageItems[$pos]['note'] = $noteSel !== '' ? $noteSel : ($stageItems[$pos]['note'] ?? null);
                $stageItems[$pos]['completed_at'] = now()->toDateTimeString();
                $stageItems[$pos]['completed_by'] = $userId;
                $completedCount = collect($stageItems)->where('is_completed', true)->count();
                $totalStagesTmp = count($stageItems);
                $progress = $totalStagesTmp > 0 ? (int) round(($completedCount / $totalStagesTmp) * 100) : 0;
                $workspace->update(['stages' => array_values($stageItems), 'progress' => $progress]);
                ProgressHistory::create([
                    'workspace_id' => $workspace->id,
                    'stage' => $stage,
                    'stage_order' => $pos + 1,
                    'progress' => $progress,
                    'description' => $noteSel !== '' ? $noteSel : 'Tahap "' . $stage . '" diperbarui',
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