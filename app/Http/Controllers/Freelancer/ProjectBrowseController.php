<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Penawaran;
use App\Models\Project;
use App\Services\NotificationService;
use App\Services\ProfileCompletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectBrowseController extends Controller
{
    public function index(Request $request): View
{
    $search = $request->query('search');
    $categoryId = $request->query('category_id');

    $query = Project::with('category')->latest();

    if ($search) {
        $query->where('project_name', 'like', "%{$search}%");
    }

    if ($categoryId) {
        $query->where('category_id', $categoryId);
    }

// Hanya tampilkan proyek yang masih menerima penawaran:
    // archive_status = active, status Open, DAN belum memiliki workspace.
    $query->where('archive_status', 'active')
        ->where('status', 'Open')
        ->whereDoesntHave('workspace');

    $projects = $query->paginate(10)->withQueryString();

    $categories = Category::orderBy('name')->get();

    $latestApplications = Penawaran::with('project')
        ->where('freelancer_id', Auth::id())
        ->latest()
        ->take(4)
        ->get();

    if ($request->route()->named('freelancer.proyek')) {
        return view('freelancer.proyek', compact(
            'projects',
            'categories',
            'search',
            'categoryId',
            'latestApplications'
        ));
    }

    return view('freelancer.projects.index', compact(
        'projects',
        'categories',
        'search',
        'categoryId',
        'latestApplications'
    ));
}

public function show(Project $project): View
    {
        $project->load('category', 'owner');

        $hasOffered = Penawaran::where('project_id', $project->id)
            ->where('freelancer_id', Auth::id())
            ->exists();

        $acceptsOffers = $project->acceptsOffers();

        return view('freelancer.projects.show', compact('project', 'hasOffered', 'acceptsOffers'));
    }
    public function create(Project $project)
{
    // ── Backend source of truth: proyek harus masih menerima penawaran ──
    if (!$project->acceptsOffers()) {
        return redirect()
            ->route('freelancer.projects.show', $project)
            ->with('error', 'Proyek ini sudah tidak menerima penawaran baru.');
    }

    // Cek apakah freelancer sudah pernah mengirim penawaran
    $alreadyOffered = Penawaran::where('project_id', $project->id)
        ->where('freelancer_id', Auth::id())
        ->exists();

    if ($alreadyOffered) {
        return redirect()
            ->route('freelancer.projects.show', $project)
            ->with('error', 'Anda sudah pernah mengirim penawaran pada proyek ini.');
    }

    return view('freelancer.penawaran.create', compact('project'));
}
public function store(Request $request, Project $project)
{
    // ── Backend source of truth: tolak jika proyek tidak menerima penawaran ──
    if (!$project->acceptsOffers()) {
        return redirect()
            ->route('freelancer.projects.show', $project)
            ->with('error', 'Proyek ini sudah tidak menerima penawaran baru.');
    }

    // Cek apakah freelancer sudah pernah mengirim penawaran
    $alreadyOffered = Penawaran::where('project_id', $project->id)
        ->where('freelancer_id', Auth::id())
        ->exists();

    if ($alreadyOffered) {
        return redirect()
            ->route('freelancer.projects.show', $project)
            ->with('error', 'Anda sudah pernah mengirim penawaran pada proyek ini.');
    }

    // Cek kelengkapan profil freelancer
    $completionService = app(ProfileCompletionService::class);
    if (!$completionService->isComplete(Auth::user())) {
        return redirect()
            ->route('freelancer.profile')
            ->with('error', 'Profil Anda belum lengkap. Silakan lengkapi minimal 80% profil terlebih dahulu agar dapat mengirim penawaran.');
    }

    $request->validate([
        'harga_penawaran' => 'required|numeric',
        'estimasi_hari'   => 'required|numeric',
        'pesan'           => 'required',
        'proposal'        => 'required|mimes:pdf|max:5120',
    ]);

    $proposal = $request->file('proposal')
        ->store('penawaran', 'public');

    $penawaran = Penawaran::create([
        'project_id'        => $project->id,
        'freelancer_id'     => Auth::id(),
        'harga_penawaran'   => $request->harga_penawaran,
        'estimasi_hari'     => $request->estimasi_hari,
        'pesan'             => $request->pesan,
        'proposal'          => $proposal,
        'status'            => 'Menunggu',
    ]);

    // Buat notifikasi untuk pemilik proyek (company)
    if ($project->owner && $project->owner->id !== Auth::id()) {
NotificationService::sendTo(
            user: $project->owner->id,
            type: 'offer.sent',
            title: 'Penawaran Baru',
            message: Auth::user()->name . ' mengirimkan penawaran untuk proyek "' . $project->project_name . '".',
            redirect: route('company.projects.show', $project),
            senderId: Auth::id(),
            penawaranId: $penawaran->id,
            projectId: $project->id,
        );
    }

   return redirect()
    ->route('freelancer.dashboard')
    ->with('success', 'Penawaran berhasil dikirim!');
    
}
}
