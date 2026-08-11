<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportEvidenceRequest;
use App\Http\Requests\ReportStoreRequest;
use App\Models\Report;
use App\Models\Project;
use App\Models\Workspace;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display a list of reports belonging to the authenticated freelancer.
     */
    public function index(): View
    {
$reports = Report::with([
                'reportedUser',
                'project',
                'penawaran',
                'workspace.project',
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
        $workspace = null;

        // Konteks: reported_user_id murni (Freelancer melaporkan Company).
        // Target ditentukan backend, bukan dari browser.
        if ($request->filled('reported_user_id')
            && !$request->filled('workspace_id')
            && !$request->filled('project_id')) {
            $reportedUser = \App\Models\User::findOrFail($request->reported_user_id);

            // Freelancer hanya boleh melaporkan company & bukan dirinya sendiri.
            if ((int) $reportedUser->id === (int) Auth::id()) {
                abort(403, 'Anda tidak dapat melaporkan diri sendiri.');
            }
            if ($reportedUser->role !== 'company') {
                abort(403, 'Anda hanya dapat melaporkan perusahaan.');
            }

            return view('freelancer.reports.create', compact(
                'project',
                'reportedUser',
                'workspace'
            ));
        }

        // Konteks dari workspace (Freelancer melaporkan Company di workspace).
        if ($request->filled('workspace_id')) {
            $workspace = Workspace::with(['project', 'company'])
                ->findOrFail($request->workspace_id);

            // Hanya freelancer yang menjadi bagian dari workspace ini.
            if ((int) $workspace->freelancer_id !== (int) Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke workspace ini.');
            }

            $project = $workspace->project;
            $reportedUser = $workspace->company;

            // Tidak boleh melaporkan diri sendiri.
            if ($reportedUser && $reportedUser->id == Auth::id()) {
                abort(403, 'Anda tidak dapat melaporkan diri sendiri.');
            }
        } elseif ($request->filled('project_id')) {

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
            'reportedUser',
            'workspace'
        ));
    }

    /**
     * Store report.
     */
public function store(ReportStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Semua validasi otorisasi relasi (project/workspace) ditangani
        // oleh ReportService::store() -> authorizeStore().
        try {
            $this->reportService->store($validated);
        } catch (ValidationException $e) {
            // Laporan ditolak (mis. duplikat) -> tampilkan pesan yang jelas.
            return Redirect::back()
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()
            ->route('freelancer.reports.index')
            ->with(
                'success',
                'Laporan berhasil dikirim. Terima kasih telah membantu menjaga kualitas platform. Tim Admin akan meninjau laporan Anda secepat mungkin.'
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
            'attachments',
        ]);

        return view('freelancer.reports.show', compact('report'));
    }

    /**
     * Unggah bukti tambahan untuk laporan berstatus 'menunggu-bukti'.
     */
    public function uploadEvidence(ReportEvidenceRequest $request, Report $report): RedirectResponse
    {
        try {
            $this->reportService->uploadEvidence($report, $request->validated('attachments'));
        } catch (ValidationException $e) {
            return Redirect::back()
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()
            ->route('freelancer.reports.show', $report)
            ->with('success', 'Bukti tambahan berhasil diunggah. Laporan Anda kini kembali ditinjau oleh Admin.');
    }
}
