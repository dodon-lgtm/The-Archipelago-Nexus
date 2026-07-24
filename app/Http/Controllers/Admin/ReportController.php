<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = Report::with([
            'reporter',
            'project',
            'handler',
        ]);

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Search by reporter name or description
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('reporter', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report): View
    {
        $report->load([
            'reporter',
            'project.owner',
            'handler',
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
            'handled_by' => auth()->id(),
            'handled_at' => now(),
        ]);

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Status laporan berhasil diperbarui.');
    }
}

