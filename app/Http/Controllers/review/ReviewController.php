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
        $project = Project::with('freelancer.freelancerProfile')->findOrFail($projectId);

        // Pastikan hanya client pemilik project yang bisa memberi review
        if ($project->client_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('company.review_create', compact('project'));
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
}