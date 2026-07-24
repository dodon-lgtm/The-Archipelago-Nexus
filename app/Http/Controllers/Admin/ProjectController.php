<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::with(['owner', 'category']);

        // Search by project name
        if ($search = $request->input('search')) {
            $query->where('project_name', 'like', "%{$search}%");
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by company
        if ($companyId = $request->input('company_id')) {
            $query->where('user_id', $companyId);
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        $companies = User::where('role', 'company')->orderBy('name')->get();

        return view('admin.projects.index', compact('projects', 'companies'));
    }

    public function show(Project $project): View
    {
        $project->load([
            'owner',
            'category',
            'penawarans.freelancer',
            'workspace.company',
            'workspace.freelancer',
        ]);

        $totalPenawarans = $project->penawarans->count();
        $acceptedPenawaran = $project->penawarans->where('status', 'Diterima')->first();

        return view('admin.projects.show', compact(
            'project',
            'totalPenawarans',
            'acceptedPenawaran'
        ));
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Proyek berhasil dihapus.');
    }
}

