<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportEvidenceRequest;
use App\Http\Requests\ReportStoreRequest;
use App\Models\Report;
use App\Models\Penawaran;
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
     * Menampilkan daftar laporan milik company.
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

        return view('company.reports.index', compact('reports'));
    }

    /**
     * Form membuat laporan.
     */
    public function create(Request $request): View
    {
        $penawaran = null;
        $project = null;
        $reportedUser = null;
        $workspace = null;

        // Konteks: reported_user_id murni (Company melaporkan Freelancer dari
        // halaman profil freelancer view-only). Target ditentukan backend,
        // bukan dari browser (browser hanya mengirim user yang dilaporkan).
        if ($request->filled('reported_user_id')
            && !$request->filled('workspace_id')
            && !$request->filled('penawaran_id')) {
            $reportedUser = \App\Models\User::findOrFail($request->reported_user_id);

            // Company hanya boleh melaporkan freelancer & bukan dirinya sendiri.
            if ((int) $reportedUser->id === (int) Auth::id()) {
                abort(403, 'Anda tidak dapat melaporkan diri sendiri.');
            }
            if ($reportedUser->role !== 'freelancer') {
                abort(403, 'Anda hanya dapat melaporkan freelancer.');
            }

            return view('company.reports.create', compact(
                'penawaran',
                'project',
                'reportedUser',
                'workspace'
            ));
        }

        // Konteks dari workspace (Company melaporkan Freelancer di workspace).
        if ($request->filled('workspace_id')) {
            $workspace = Workspace::with(['project', 'freelancer'])
                ->findOrFail($request->workspace_id);

            // Hanya company yang menjadi bagian dari workspace ini.
            if ((int) $workspace->company_id !== (int) Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke workspace ini.');
            }

            $project = $workspace->project;
            $reportedUser = $workspace->freelancer;

            // Tidak boleh melaporkan diri sendiri.
            if ($reportedUser && $reportedUser->id == Auth::id()) {
                abort(403, 'Anda tidak dapat melaporkan diri sendiri.');
            }
        } elseif ($request->filled('penawaran_id')) {

            $penawaran = Penawaran::with([
                'freelancer',
                'project.owner'
            ])->findOrFail($request->penawaran_id);

            // Pastikan company hanya bisa melaporkan penawaran miliknya
            if ((int) $penawaran->project->user_id !== (int) Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke penawaran ini.');
            }

            $project = $penawaran->project;
            $reportedUser = $penawaran->freelancer;

            // Tidak boleh melaporkan diri sendiri
            if ($reportedUser && $reportedUser->id == Auth::id()) {
                abort(403, 'Anda tidak dapat melaporkan diri sendiri.');
            }
        }

        return view('company.reports.create', compact(
            'penawaran',
            'project',
            'reportedUser',
            'workspace'
        ));
    }

    /**
     * Simpan laporan.
     */
public function store(ReportStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Semua validasi otorisasi relasi (project/penawaran/workspace) ditangani
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
            ->route('company.reports.index')
            ->with(
                'success',
                'Laporan berhasil dikirim. Terima kasih telah membantu menjaga kualitas platform. Tim Admin akan meninjau laporan Anda secepat mungkin.'
            );
    }

/**
     * Detail laporan.
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

        return view('company.reports.show', compact('report'));
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
            ->route('company.reports.show', $report)
            ->with('success', 'Bukti tambahan berhasil diunggah. Laporan Anda kini kembali ditinjau oleh Admin.');
    }
}
