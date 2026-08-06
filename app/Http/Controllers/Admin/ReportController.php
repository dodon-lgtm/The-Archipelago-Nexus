<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReportUpdateStatusRequest;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request): View
    {
$query = Report::with([
            'reporter',
            'project',
            'reportedUser',
            'workspace',
        ]);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($target = $request->input('target')) {
            $query->where('target', $target);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('reporter', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

public function show(Report $report): View
    {
        $report->load([
            'reporter',
            'reportedUser',
            'project.owner',
            'penawaran.freelancer',
            'workspace.project',
            'handledBy',
            'attachments',
        ]);

        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(ReportUpdateStatusRequest $request, Report $report): RedirectResponse
    {
        $this->reportService->updateStatus(
            $report,
            $request->validated('status'),
            $request->validated('admin_note'),
            Auth::id()
        );

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Hapus project yang terkait dengan laporan (Kasus 1 - Freelancer lapor Company).
     */
    public function destroyProject(Request $request, Report $report): RedirectResponse
    {
        $project = $report->project;

        if (!$project) {
            return redirect()
                ->route('admin.reports.show', $report)
                ->with('error', 'Data project sudah tidak tersedia.');
        }

        DB::transaction(function () use ($project, $report) {
            $report->update(['project_id' => null]);
            $project->delete();
        });

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Project berhasil dihapus.');
    }

    /**
     * Hapus penawaran yang terkait dengan laporan (Kasus 2 - Company lapor Freelancer).
     */
    public function destroyPenawaran(Request $request, Report $report): RedirectResponse
    {
        $penawaran = $report->penawaran;

        if (!$penawaran) {
            return redirect()
                ->route('admin.reports.show', $report)
                ->with('error', 'Data penawaran sudah tidak tersedia.');
        }

        DB::transaction(function () use ($penawaran, $report) {
            $report->update(['penawaran_id' => null]);
            $penawaran->delete();
        });

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Penawaran berhasil dihapus.');
    }
}
