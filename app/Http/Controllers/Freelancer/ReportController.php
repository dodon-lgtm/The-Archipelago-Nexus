<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    /**
     * Display a list of reports belonging to the authenticated freelancer.
     */
    public function index(): View
    {
        $reports = Report::with([
                'reportedUser',
                'project',
                'penawaran'
            ])
            ->where('reporter_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('freelancer.reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create(Request $request): View
    {
        $project = null;
        $reportedUser = null;

        // Jika berasal dari halaman detail proyek
        if ($request->filled('project_id')) {

            $project = Project::with('owner')
                ->findOrFail($request->project_id);

            $reportedUser = $project->owner;

            // Tidak boleh melaporkan proyek sendiri
            if ($reportedUser && $reportedUser->id == Auth::id()) {
                abort(403, 'Anda tidak dapat melaporkan proyek Anda sendiri.');
            }
        }

        return view('freelancer.reports.create', compact(
            'project',
            'reportedUser'
        ));
    }

    /**
     * Store report.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject'          => 'required|string|max:255',
            'description'      => 'required|string|max:5000',
            'reported_user_id' => 'nullable|exists:users,id',
            'project_id'       => 'nullable|exists:projects,id',
            'penawaran_id'     => 'nullable|exists:penawarans,id',
        ]);

        if ($request->filled('project_id')) {

            $project = Project::with('owner')
                ->find($request->project_id);

            if (!$project) {
                return back()
                    ->withErrors([
                        'project_id' => 'Proyek tidak ditemukan.'
                    ])
                    ->withInput();
            }

            $ownerId = optional($project->owner)->id;

            if (
                $request->filled('reported_user_id') &&
                (int) $request->reported_user_id !== $ownerId
            ) {
                return back()
                    ->withErrors([
                        'reported_user_id' => 'Pengguna yang dilaporkan tidak sesuai dengan proyek.'
                    ])
                    ->withInput();
            }

            if ($project->user_id == Auth::id()) {
                return back()
                    ->withErrors([
                        'project_id' => 'Anda tidak dapat melaporkan proyek Anda sendiri.'
                    ])
                    ->withInput();
            }
        }

        Report::create([
            'reporter_id'      => Auth::id(),
            'reported_user_id' => $validated['reported_user_id'] ?? null,
            'project_id'       => $validated['project_id'] ?? null,
            'penawaran_id'     => $validated['penawaran_id'] ?? null,
            'subject'          => $validated['subject'],
            'description'      => $validated['description'],
            'status'           => 'menunggu',
        ]);

        return redirect()
            ->route('freelancer.reports.index')
            ->with(
                'success',
                'Laporan berhasil dikirim. Admin akan segera meninjau laporan Anda.'
            );
    }

    /**
     * Show report detail.
     */
    public function show(Report $report): View
    {
        if ($report->reporter_id != Auth::id()) {
            abort(403);
        }

        $report->load([
            'reporter',
            'reportedUser',
            'project.owner',
            'penawaran.freelancer',
        ]);

        return view('freelancer.reports.show', compact('report'));
    }
}