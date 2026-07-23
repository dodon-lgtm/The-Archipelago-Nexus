<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\ProjectStoreRequest;
use App\Http\Requests\Company\ProjectUpdateRequest;
use App\Models\Penawaran;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; // Tambahkan ini

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
        try {
            // 1. Ambil data tervalidasi
            $data = $request->validated();

            // 2. Upload file (jika ada)
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('projects/images', 'public');
            }

            if ($request->hasFile('attachment')) {
                $data['attachment'] = $request->file('attachment')->store('projects/attachments', 'public');
            }

            // 3. Set user_id
            $data['user_id'] = auth()->id();

            // 4. Simpan ke database
            Project::create($data);

            return redirect()
                ->route('company.dashboard')
                ->with('success', 'Proyek berhasil dibuat.');

        } catch (\Exception $e) {
            // Jika gagal, catat error ke file storage/logs/laravel.log
            Log::error("Gagal simpan project: " . $e->getMessage());
            
            // Kembalikan ke halaman sebelumnya dengan pesan error
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()]);
        }
    }

    public function show(Project $project): View
    {
        $this->authorizeCompanyProject($project);
        $project->load('penawarans.freelancer');
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

        // Pastikan penawaran milik project ini
        abort_unless((int) $penawaran->project_id === (int) $project->id, 403);

        // Pastikan penawaran masih berstatus Menunggu
        abort_if($penawaran->status !== 'Menunggu', 422, 'Penawaran sudah diproses sebelumnya.');

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

        return redirect()
            ->route('company.projects.show', $project)
            ->with('success', 'Freelancer berhasil dipilih. Penawaran lainnya otomatis ditolak.');
    }

    private function authorizeCompanyProject(Project $project): void
    {
        abort_unless((int) $project->user_id === (int) auth()->id(), 403);
    }
}
