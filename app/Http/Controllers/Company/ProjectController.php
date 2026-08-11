<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\ProjectStoreRequest;
use App\Http\Requests\Company\ProjectUpdateRequest;
use App\Models\Penawaran;
use App\Models\Project;
use App\Models\Workspace;
use App\Models\ProgressHistory;
use App\Models\Message;
use App\Services\NotificationService;
use App\Services\ProfileCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
public function index(): View
    {
        // Hanya project aktif (belum diarsip/nonaktif) yang tampil di halaman utama.
        $projects = Project::with('workspace')
            ->where('user_id', Auth::id())
            ->where('archive_status', 'active')
            ->latest()
            ->paginate(10);

        return view('company.projects.index', compact('projects'));
    }

    /**
     * Halaman Arsip Proyek — menampilkan project yang diarsipkan / nonaktif.
     */
    public function archiveIndex(): View
    {
        $archivedProjects = Project::query()
            ->where('user_id', Auth::id())
            ->whereIn('archive_status', ['archived', 'inactive'])
            ->with(['workspace', 'penawarans'])
            ->latest()
            ->paginate(10);

        return view('company.projects.archive', compact('archivedProjects'));
    }

    /**
     * ARSIPKAN project (archive_status = 'archived').
     *
     * Aksi administratif murni: hanya mengubah projects.archive_status.
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
     * AKTIFKAN KEMBALI (archive_status = 'active').
     *
     * HANYA mengeluarkan project dari arsip secara administratif.
     * Tidak menghidupkan kembali Workspace/kontrak lama.
     */
    public function activate(Project $project): RedirectResponse
    {
        $this->authorizeCompanyProject($project);

        if (!$project->isArchived() && !$project->isInactive()) {
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
     * NONAKTIFKAN (archive_status = 'inactive').
     *
     * Project tidak menerima penawaran baru. Tidak mengubah Workspace.
     */
    public function deactivate(Project $project): RedirectResponse
    {
        $this->authorizeCompanyProject($project);

        if ($project->isInactive()) {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Proyek sudah berstatus nonaktif.');
        }

        $project->deactivate();

        return redirect()
            ->route('company.projects.show', $project)
            ->with('success', 'Proyek berhasil dinonaktifkan.');
    }

    public function create(): View
    {
        $categories = \App\Models\Category::query()->orderBy('name')->get();
        return view('company.projects.create', compact('categories'));
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
        foreach ($lock['locked'] as $field) {
            unset($data[$field]);
        }

        // Status tidak boleh diubah lewat form edit (khusus via aksi "Tutup Project").
        unset($data['status']);

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
     * - Status masih 'Open'.
     * - Belum memiliki Workspace (pekerjaan belum berjalan).
     * - Belum selesai (Workspace.status = Selesai).
     *
     * 'Closed' BUKAN berarti proyek selesai. Proyek dianggap selesai
     * hanya ketika Workspace.status = 'Selesai'.
     */
    public function close(Project $project): RedirectResponse
    {
        $this->authorizeCompanyProject($project);

        if ($project->status !== 'Open') {
            return redirect()
                ->route('company.projects.show', $project)
                ->with('error', 'Hanya proyek berstatus Open yang dapat ditutup.');
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

        $project->update(['status' => 'Closed']);

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
                    'budget', 'deadline', 'status',
                ],
                'note' => 'Proyek sudah menerima penawaran. Budget dan deadline tidak dapat diubah.',
            ];
        }

        return [
            'locked' => [ 'status' ],
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

            // Buat Workspace untuk project
            $workspace = Workspace::create([
                'project_id' => $project->id,
                'company_id' => Auth::id(),
                'freelancer_id' => $penawaran->freelancer_id,
                'status' => 'Sedang Dikerjakan',
            ]);

            // Buat Progress History pertama
            ProgressHistory::create([
                'workspace_id' => $workspace->id,
                'stage' => 'Dipilih',
                'progress' => 5,
                'description' => 'Freelancer dipilih oleh perusahaan.',
                'updated_by' => Auth::id(),
            ]);

            // Buat System Message pertama
            Message::create([
                'workspace_id' => $workspace->id,
                'sender_id' => Auth::id(),
                'message' => 'Perusahaan telah memilih freelancer dan workspace proyek telah dibuat.',
                'type' => 'system',
            ]);

            // Notifikasi untuk freelancer yang dipilih
            NotificationService::sendTo(
                user: $penawaran->freelancer_id,
                type: 'offer.accepted',
                title: 'Penawaran Diterima',
                message: 'Selamat! Penawaran Anda untuk proyek "' . $project->project_name . '" telah diterima. Workspace proyek telah dibuat.',
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
                ->route('company.projects.show', $project)
                ->with('success', 'Freelancer berhasil dipilih. Workspace proyek telah dibuat.');

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
