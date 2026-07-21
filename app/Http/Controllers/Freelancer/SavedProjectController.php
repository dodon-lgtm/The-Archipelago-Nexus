<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SavedProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SavedProjectController extends Controller
{
    public function index(Request $request): View
    {
        $savedProjects = SavedProject::with([
            'project.category',
            'project.owner'
        ])
            ->where('freelancer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('freelancer.simpan', compact('savedProjects'));
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $freelancerId = Auth::id();

        $exists = SavedProject::where('freelancer_id', $freelancerId)
            ->where('project_id', $project->id)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->with('info', 'Proyek ini sudah tersimpan.');
        }

        SavedProject::create([
            'freelancer_id' => $freelancerId,
            'project_id' => $project->id,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Proyek berhasil disimpan.');
    }

    public function destroy(Request $request, Project $project): RedirectResponse
    {
        $deleted = SavedProject::where('freelancer_id', Auth::id())
            ->where('project_id', $project->id)
            ->delete();

        if ($deleted) {
            return redirect()
                ->back()
                ->with('success', 'Proyek berhasil dihapus dari tersimpan.');
        }

        return redirect()
            ->back()
            ->with('error', 'Proyek tidak ditemukan di daftar tersimpan.');
    }
}