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

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('company.projects.index', compact('projects'));
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
        if (!$completionService->isComplete(auth()->user())) {
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

            $data['user_id'] = auth()->id();

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
        return view('company.projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $this->authorizeCompanyProject($project);
        $categories = \App\Models\Category::query()->orderBy('name')->get();
        return view('company.projects.edit', compact('project', 'categories'));
    }

    public function update(ProjectUpdateRequest $request, Project $project): RedirectResponse
    {
        $this->authorizeCompanyProject($project);
        $project->update($request->validated());

        return redirect()
            ->route('company.projects.show', $project)
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorizeCompanyProject($project);
        $project->delete();

        return redirect()
            ->route('company.projects.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }

    public function selectFreelancer(Project $project, Penawaran $penawaran): RedirectResponse
    {
        $this->authorizeCompanyProject($project);

        // Cek kelengkapan profil company
        $completionService = app(ProfileCompletionService::class);
        if (!$completionService->isComplete(auth()->user())) {
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
                'company_id' => auth()->id(),
                'freelancer_id' => $penawaran->freelancer_id,
                'status' => 'Sedang Dikerjakan',
            ]);

            // Buat Progress History pertama
            ProgressHistory::create([
                'workspace_id' => $workspace->id,
                'stage' => 'Dipilih',
                'progress' => 5,
                'description' => 'Freelancer dipilih oleh perusahaan.',
                'updated_by' => auth()->id(),
            ]);

            // Buat System Message pertama
            Message::create([
                'workspace_id' => $workspace->id,
                'sender_id' => auth()->id(),
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
                senderId: auth()->id(),
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
                    senderId: auth()->id(),
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
        abort_unless((int) $project->user_id === (int) auth()->id(), 403);
    }
}
