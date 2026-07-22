<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Penawaran;
use App\Models\Project;
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

        return view('freelancer.projects.show', compact('project'));
    }
    public function create(Project $project)
{
    return view('freelancer.penawaran.create', compact('project'));
}
public function store(Request $request, Project $project)
{
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
        Notification::create([
            'user_id'       => $project->owner->id,
            'penawaran_id'  => $penawaran->id,
            'title'         => 'Penawaran Baru',
            'message'       => Auth::user()->name . ' mengirimkan penawaran untuk proyek "' . $project->project_name . '".',
            'is_read'       => false,
        ]);
    }

   return redirect()
    ->route('freelancer.dashboard')
    ->with('success', 'Penawaran berhasil dikirim!');
    
}
}

