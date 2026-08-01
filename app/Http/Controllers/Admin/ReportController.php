<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = Report::with([
            'reporter',
            'project',
            'reportedUser',
        ]);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
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
        ]);

        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:diproses,selesai,ditolak',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $report->update([
            'status' => $validated['status'],
            'admin_note' => $validated['admin_note'] ?? $report->admin_note,
        ]);

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
