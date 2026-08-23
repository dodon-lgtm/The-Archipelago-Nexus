<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\ProjectStoreRequest;
use App\Http\Requests\Company\ProjectUpdateRequest;
use App\Models\Penawaran;
use App\Models\Project;
use App\Models\Workspace;
use App\Models\Payment;
use App\Models\ProgressHistory;
use App\Models\Message;
use App\Services\NotificationService;
use App\Services\ProfileCompletionService;
use App\Services\ProjectQuotaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(): View
    {
        // Hanya project berstatus open / closed yang tampil di halaman utama.
        // Project 'archived' masuk ke halaman arsip.
        $projects = Project::with('workspace')
            ->where('user_id', Auth::id())
            ->whereIn('status', [Project::STATUS_OPEN, Project::STATUS_CLOSED])
            ->latest()
            ->paginate(10);

        return view('company.projects.index', compact('projects'));
    }

    /**
     * Halaman Arsip Proyek — menampilkan project yang diarsipkan.
     */
    public function archiveIndex(): View
    {
        $archivedProjects = Project::query()
            ->where('user_id', Auth::id())
            ->where('status', Project::STATUS_ARCHIVED)
            ->with(['workspace', 'penawarans'])
            ->latest()
            ->paginate(10);

        return view('company.projects.archive', compact('archivedProjects'));
    }

    /**
     * ARSIPKAN project (status = 'archived').
     *
     * Aksi administratif murni: hanya mengubah projects.status.
     * TIDAK mengubah Workspace / kontrak lama.
     * Setiap project boleh diarsipkan (source of truth di backend).
     */
    public function archive(Project $project): RedirectResponse
    {
        $this->authorizeCompanyProject($project);

        if ($project->isArchived()) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Proyek sudah diarsipkan.');
        }

        $project->archive();

        return redirect()
            ->route('company.projects.index')
            ->with('success', 'Proyek berhasil diarsipkan.');
    }

    /**
     * AKTIFKAN KEMBALI (status = 'open').
     *
     * HANYA mengeluarkan project dari arsip secara administratif.
     * Tidak menghidupkan kembali Workspace/kontrak lama.
     */
    public function activate(Project $project): RedirectResponse
    {
        $this->authorizeCompanyProject($project);

        if (!$project->isArchived()) {
            return redirect()
                ->route('company.projects.index')
                ->with('error', 'Proyek sudah berstatus aktif.');
        }

        // Project yang sudah SELESAI tidak boleh diaktifkan kembali
        // (Workspace Selesai adalah histori permanen).
        if ($project->isCompleted()) {
            return redirect()
                ->route('company.projects.archive')
                ->with('error', 'Proyek yang sudah selesai tidak dapat diaktifkan kembali.');
        }

        $project->activate();

        return redirect()
            ->route('company.projects.index')
            ->with('success', 'Proyek berhasil diaktifkan kembali.');
    }

    /**
     * NONAKTIFKAN / TUTUP (status = 'closed').
     *
     * Project tidak menerima penawaran baru. Tidak mengubah Workspace.
     */
    public function deactivate(Project $project): RedirectResponse
    {
        $this->authorizeCompanyProject($project);

        if ($project->isClosed()) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Proyek sudah berstatus Tutup.');
        }

        $project->deactivate();

        return redirect()
            ->route('company.projects.show', $project)
            ->with('success', 'Proyek berhasil ditutup.');
    }

        public function create(): View
    {
        $categories = \App\Models\Category::query()->orderBy('name')->get();

        $quota = new ProjectQuotaService();
        $quotaData = [
            'can_create'      => $quota->canCreateProject(Auth::id()),
            'used_slots'      => $quota->usedSlots(Auth::id()),
            'available_slots' => $quota->availableSlots(Auth::id()),
            'free_quota'      => ProjectQuotaService::FREE_QUOTA_PER_MONTH,
        ];

        return view('company.projects.create', compact('categories', 'quotaData'));
    }

    public function store(ProjectStoreRequest $request): RedirectResponse
    {
        // Cek kelengkapan profil company
        $completionService = app(ProfileCompletionService::class);
        if (!$completionService->isComplete(Auth::user())) {
            return redirect()
                ->route('company.profile')
                ->with('error', 'Profil Anda belum lengkap. Silakan lengkapi minimal 80% profil terlebih dahulu agar dapat membuat proyek.');
        }

        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('projects/images', 'public');
            }

            if ($request->hasFile('attachment')) {
                $data['attachment'] = $request->file('attachment')->store('projects/attachments', 'public');
            }

                        $data['user_id'] = Auth::id();

            // Kuota bulan berjalan: blokir pembuatan jika sudah melebihi batas
            $quotaService = new ProjectQuotaService();
            if (!$quotaService->canCreateProject(Auth::id())) {
                return redirect()
                    ->route('company.projects.create')
                    ->with('quota_blocked', true)
                    ->with('quota_message', 'Kuota proyek bulan ini telah penuh. Bayar Rp10.000 untuk menambah slot.');
            }

            Project::create($data);

            return redirect()
                ->route('company.dashboard')
                ->with('success', 'Proyek berhasil dibuat.');

        } catch (\Exception $e) {
            Log::error("Gagal simpan project: " . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()]);
        }
    }

    public function show(Project $project): View
    {
        $this->authorizeCompanyProject($project);
        $project->load(['penawarans.freelancer', 'workspace']);

        $lock = $this->workflowLock($project);

        return view('company.projects.show', compact('project', 'lock'));
    }

    public function edit(Project $project): View
    {
        $this->authorizeCompanyProject($project);
        $categories = \App\Models\Category::query()->orderBy('name')->get();

        $lock = $this->workflowLock($project);

        return view('company.projects.edit', compact('project', 'categories', 'lock'));
    }

    public function update(ProjectUpdateRequest $request, Project $project): RedirectResponse
    {
        $this->authorizeCompanyProject($project);

        // Project yang sudah SELESAI (Workspace.status = Selesai) tidak boleh diedit lagi.
        if ($project->isCompleted()) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Proyek sudah selesai dan tidak dapat diubah lagi.');
        }

        $data = $request->validated();

        // ── Backend source of truth: kunci field sesuai workflow ────────────
        $lock = $this->workflowLock($project);

        // Field yang TIDAK boleh diubah ketika sudah memasuki alur terikat.
        // Status ikut terkunci lewat sini (mis. proyek sedang dikerjakan / selesai),
        // sehingga perubahan status dari form hanya dipakai bila memang diizinkan.
        foreach ($lock['locked'] as $field) {
            unset($data[$field]);
        }

        // ── File upload (image / attachment) ────────────────────────────────
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects/images', 'public');
        }

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('projects/attachments', 'public');
        }

        $project->update($data);

        return redirect()
            ->route('company.projects.show', $project)
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    /**
     * TUTUP PROYEK — menghentikan penerimaan penawaran baru.
     *
     * Hanya berlaku jika:
     * - Status masih 'open'.
     * - Belum memiliki Workspace (pekerjaan belum berjalan).
     * - Belum selesai (Workspace.status = Selesai).
     *
     * 'closed' BUKAN berarti proyek selesai. Proyek dianggap selesai
     * hanya ketika Workspace.status = 'Selesai'.
     */
    public function close(Project $project): RedirectResponse
    {
        $this->authorizeCompanyProject($project);

        // Jika proyek sudah berstatus closed, arahkan kembali ke halaman detail/show proyek
        if ($project->isClosed()) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Proyek ini memang sudah berstatus Tutup.');
        }

        if (!$project->isOpen()) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Hanya proyek berstatus Open yang dapat ditutup. Status saat ini: ' . Project::statusLabel($project->status));
        }

        if ($project->workspace()->exists()) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Proyek sudah memiliki workspace / sedang dikerjakan. Tidak dapat ditutup.');
        }

        if ($project->isCompleted()) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Proyek sudah selesai.');
        }

        $project->update(['status' => Project::STATUS_CLOSED]);

        return redirect()
            ->route('company.projects.show', $project)
            ->with('success', 'Proyek berhasil ditutup. Proyek tidak lagi menerima penawaran baru.');
    }

    /**
     * Menghitung field yang terkunci berdasarkan tahapan workflow project.
     *
     * @return array{locked: string[], note: string}
     */
    private function workflowLock(Project $project): array
    {
        $hasWorkspace = $project->workspace()->exists();
        $hasOffers = $project->penawarans()->exists();
        $completed = $project->isCompleted();

        if ($completed) {
            return [
                'locked' => [
                    'project_name', 'project_description', 'category_id',
                    'budget', 'deadline', 'skills', 'image', 'attachment', 'status',
                ],
                'note' => 'Proyek sudah selesai dan tidak dapat diubah lagi.',
            ];
        }

        if ($hasWorkspace) {
            return [
                'locked' => [
                    'budget', 'deadline', 'skills', 'status',
                ],
                'note' => 'Proyek sedang dikerjakan. Budget, deadline, skill, dan status tidak dapat diubah.',
            ];
        }

        if ($hasOffers) {
            return [
                'locked' => [
                    'budget', 'deadline',
                ],
                'note' => 'Proyek sudah menerima penawaran. Budget dan deadline tidak dapat diubah.',
            ];
        }

        return [
            'locked' => [],
            'note' => 'Semua field dapat diubah.',
        ];
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorizeCompanyProject($project);

        // BACKEND SOURCE OF TRUTH:
        // Project HANYA boleh dihapus jika BELUM memiliki penawaran DAN belum memiliki Workspace.
        // Jika sudah ada penawaran atau Workspace, tolak dan arahkan untuk mengarsipkan.
        $hasOffers = $project->penawarans()->exists();
        $hasWorkspace = $project->workspace()->exists();

        if ($hasOffers || $hasWorkspace) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Proyek tidak dapat dihapus karena sudah memiliki penawaran atau workspace. Silakan arsipkan proyek ini untuk menyimpan histori.');
        }

        $project->delete();

        return redirect()
            ->route('company.projects.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }

    public function selectFreelancer(Project $project, Penawaran $penawaran): RedirectResponse
    {
        $this->authorizeCompanyProject($project);

        // Project yang sudah SELESAI tidak boleh membuat Workspace baru lagi.
        if ($project->isCompleted()) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Proyek sudah selesai dan tidak dapat membuat workspace baru.');
        }

        // Project yang diarsip/nonaktif tidak boleh membuat workspace baru.
        if (!$project->acceptsOffers()) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Proyek tidak aktif atau sudah ditutup. Tidak dapat membuat workspace baru.');
        }

        // Cek kelengkapan profil company
        $completionService = app(ProfileCompletionService::class);
        if (!$completionService->isComplete(Auth::user())) {
            return redirect()
                ->route('company.profile')
                ->with('error', 'Profil Anda belum lengkap. Silakan lengkapi minimal 80% profil terlebih dahulu agar dapat memilih freelancer.');
        }

        // Pastikan penawaran milik project ini
        abort_unless((int) $penawaran->project_id === (int) $project->id, 403);

        // Pastikan project belum memiliki freelancer yang diterima
        $alreadyAccepted = Penawaran::where('project_id', $project->id)
            ->where('status', 'Diterima')
            ->exists();

        if ($alreadyAccepted) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Project ini sudah memiliki freelancer yang diterima.');
        }

        // Pastikan penawaran masih berstatus Menunggu
        if ($penawaran->status !== 'Menunggu') {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Penawaran sudah diproses sebelumnya.');
        }

        // Pastikan project belum memiliki workspace
        if ($project->workspace()->exists()) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Workspace untuk project ini sudah ada.');
        }

        // Jalankan semua proses dalam Database Transaction
        DB::beginTransaction();

        try {
            // Ubah status penawaran terpilih menjadi Diterima + selected_at
            $penawaran->update([
                'status' => 'Diterima',
                'selected_at' => now(),
            ]);

            // Tolak semua penawaran lain pada project yang sama
            Penawaran::where('project_id', $project->id)
                ->where('id', '!=', $penawaran->id)
                ->where('status', 'Menunggu')
                ->update(['status' => 'Ditolak']);

// Hitung biaya pembayaran berdasarkan harga penawaran
            $amount = (float) $penawaran->harga_penawaran;
            $platformFee = round($amount * 5 / 100, 2);
            $freelancerReceive = $amount - $platformFee;

            // Generate invoice number unik per tanggal
            $date = now()->format('Ymd');
            $lastInvoice = Payment::whereDate('created_at', now()->toDateString())
                ->orderBy('id', 'desc')
                ->first();

            if ($lastInvoice) {
                $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            $invoiceNumber = 'INV-' . $date . '-' . $newNumber;

            // Buat Workspace untuk project dengan status Menunggu Pembayaran
            // `stages` diinisialisasi sejak awal agar workspace memiliki daftar tahap yang valid
            $workspace = Workspace::create([
                'project_id' => $project->id,
                'company_id' => Auth::id(),
                'freelancer_id' => $penawaran->freelancer_id,
                'status' => 'Menunggu Pembayaran',
                'stages' => ['Analisis Kebutuhan', 'Desain', 'Backend', 'Frontend', 'Testing'],
            ]);

            // Buat record Payment awal dengan status pending
            Payment::create([
                'workspace_id' => $workspace->id,
                'company_id' => Auth::id(),
                'freelancer_id' => $penawaran->freelancer_id,
                'invoice_number' => $invoiceNumber,
                'amount' => $amount,
                'platform_fee' => $platformFee,
                'freelancer_receive' => $freelancerReceive,
                'status' => 'pending',
            ]);

            // Buat Progress History pertama (tanda "freelancer dipilih").
            // stage_order = 0 => pekerjaan BELUM dimulai => progress 0%.
            ProgressHistory::create([
                'workspace_id' => $workspace->id,
                'stage' => 'Dipilih',
                'stage_order' => 0,
                'progress' => 0,
                'description' => 'Freelancer dipilih oleh perusahaan. Menunggu pembayaran awal.',
                'updated_by' => Auth::id(),
            ]);
        

            // Buat System Message pertama
            Message::create([
                'workspace_id' => $workspace->id,
                'sender_id' => Auth::id(),
                'message' => 'Perusahaan telah memilih freelancer. Workspace dibuat dengan status Menunggu Pembayaran.',
                'type' => 'system',
            ]);

            // System message untuk invoice
            Message::create([
                'workspace_id' => $workspace->id,
                'sender_id' => Auth::id(),
                'message' => 'Invoice ' . $invoiceNumber . ' telah diterbitkan. Silakan perusahaan menyelesaikan pembayaran.',
                'type' => 'system',
            ]);

            // Notifikasi untuk freelancer yang dipilih
            NotificationService::sendTo(
                user: $penawaran->freelancer_id,
                type: 'offer.accepted',
                title: 'Penawaran Diterima',
                message: 'Selamat! Penawaran Anda untuk proyek "' . $project->project_name . '" telah diterima. Menunggu pembayaran dari perusahaan sebelum pengerjaan proyek dimulai.',
                redirect: route('freelancer.workspaces.show', $workspace),
                senderId: Auth::id(),
                penawaranId: $penawaran->id,
                projectId: $project->id,
                workspaceId: $workspace->id,
            );

            // Notifikasi untuk freelancer lain yang ditolak
            $rejectedPenawarans = Penawaran::where('project_id', $project->id)
                ->where('id', '!=', $penawaran->id)
                ->where('status', 'Ditolak')
                ->get();

            foreach ($rejectedPenawarans as $rejected) {
                NotificationService::sendTo(
                    user: $rejected->freelancer_id,
                    type: 'offer.rejected',
                    title: 'Penawaran Ditolak',
                    message: 'Maaf, penawaran Anda untuk proyek "' . $project->project_name . '" telah ditolak karena perusahaan memilih freelancer lain.',
                    redirect: route('freelancer.projects.show', $project),
                    senderId: Auth::id(),
                    penawaranId: $rejected->id,
                    projectId: $project->id,
                );
            }

            DB::commit();

            return redirect()
                ->route('company.payments.gateway', $workspace)
                ->with('success', 'Freelancer berhasil dipilih. Silakan selesaikan pembayaran untuk memulai proyek.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal memilih freelancer: ' . $e->getMessage());

            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Terjadi kesalahan saat memproses pemilihan freelancer. Silakan coba lagi.');
        }
    }

    private function authorizeCompanyProject(Project $project): void
    {
        abort_unless((int) $project->user_id === (int) Auth::id(), 403);
    }
}