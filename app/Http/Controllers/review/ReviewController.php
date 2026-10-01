<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Workspace;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
public function create($projectId)
    {
        $project = Project::with(['workspace.freelancer', 'workspace.freelancer.freelancerProfile'])->findOrFail($projectId);

        // Pastikan hanya company pemilik project (user_id) yang bisa memberi review
        if ((int) $project->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Resolusi freelancer melalui relasi project -> workspace -> freelancer
        $freelancer = optional($project->workspace)->freelancer;

        if (!$freelancer) {
            return back()->with('error', 'Freelancer tidak ditemukan pada workspace proyek ini.');
        }

        return view('company.review_create', compact('project', 'freelancer'));
    }

    // Menyimpan ulasan ke database
    public function store(Request $request, $projectId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $project = Project::findOrFail($projectId);

        // Cari data workspace yang berelasi dengan project ini
        $workspace = Workspace::where('project_id', $project->id)->first();

        if (!$workspace || !$workspace->freelancer_id) {
            return back()->with('error', 'Freelancer tidak ditemukan pada workspace ini.');
        }

        // Cek apakah workspace ini sudah pernah di-review sebelumnya
        $existingReview = Review::where('workspace_id', $workspace->id)->first();
        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk workspace ini.');
        }

        // Simpan data review dengan menyertakan workspace_id
        Review::create([
            'workspace_id'  => $workspace->id, // <-- Ini kunci agar terdeteksi di model Workspace
            'project_id'    => $project->id,
            'client_id'     => Auth::id(),
            'freelancer_id' => $workspace->freelancer_id,
            'rating'        => $request->rating,
            'review'        => $request->review,
        ]);

        return back()->with('success', 'Ulasan dan rating berhasil dikirim.');
    }

    /**
     * Simpan ulasan berbasis WORKSPACE — dipakai modal "Beri Rating & Ulasan"
     * pada halaman detail workspace (resources/views/workspace/show.blade.php).
     *
     * Latar belakang: modal mengirim ID workspace. Route client.review.store
     * berbasis {project} sehingga ID workspace masuk ke slot project dan
     * Project::findOrFail() melempar 404 NotFound. Method ini menerima
     * workspace secara langsung + guard kepemilikan company.
     */
    public function storeForWorkspace(Request $request, Workspace $workspace)
    {
        // Hanya company pemilik workspace yang boleh memberi rating.
        if ((int) $workspace->company_id !== (int) Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk memberi rating pada workspace ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        if (!$workspace->freelancer_id) {
            return back()->with('error', 'Freelancer tidak ditemukan pada workspace ini.');
        }

        // Cegah review ganda untuk workspace yang sama.
        $existingReview = Review::where('workspace_id', $workspace->id)->first();
        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk workspace ini.');
        }

        Review::create([
            'workspace_id'  => $workspace->id,
            'project_id'    => $workspace->project_id,
            'client_id'     => Auth::id(),
            'freelancer_id' => $workspace->freelancer_id,
            'rating'        => (int) $request->rating,
            'review'        => $request->review,
        ]);

        return redirect()
            ->route('company.workspaces.show', $workspace)
            ->with('success', 'Ulasan dan rating berhasil dikirim.');
    }
}