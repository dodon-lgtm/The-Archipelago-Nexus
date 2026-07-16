<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\ProjectStoreRequest;
use App\Http\Requests\Company\ProjectUpdateRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
        return view('company.projects.create');
    }

    public function store(ProjectStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Project::create([
            ...$data,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('company.projects.index')
            ->with('success', 'Proyek berhasil dibuat.');
    }

    public function show(Project $project): View
    {
        $this->authorizeCompanyProject($project);

        return view('company.projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $this->authorizeCompanyProject($project);

        return view('company.projects.edit', compact('project'));
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

    private function authorizeCompanyProject(Project $project): void
    {
        abort_unless((int) $project->user_id === (int) auth()->id(), 403);
    }
}

