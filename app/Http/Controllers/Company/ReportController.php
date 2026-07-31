<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Project;
use App\Models\Penawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    /**
     * Menampilkan daftar laporan milik company.
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

        if ($request->filled('penawaran_id')) {

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
            'reportedUser'
        ));
    }

    /**
     * Simpan laporan.
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

        if ($request->filled('penawaran_id')) {

            $penawaran = Penawaran::with([
                'freelancer',
                'project'
            ])->find($request->penawaran_id);

            if (!$penawaran) {
                return back()
                    ->withErrors([
                        'penawaran_id' => 'Penawaran tidak ditemukan.'
                    ])
                    ->withInput();
            }

            // Pastikan penawaran milik company yang login
            if ((int) $penawaran->project->user_id !== (int) Auth::id()) {
                return back()
                    ->withErrors([
                        'penawaran_id' => 'Anda tidak memiliki akses ke penawaran ini.'
                    ])
                    ->withInput();
            }

            // Pastikan project sesuai
            if (
                $request->filled('project_id') &&
                (int) $request->project_id !== (int) $penawaran->project_id
            ) {
                return back()
                    ->withErrors([
                        'project_id' => 'Proyek tidak sesuai dengan penawaran.'
                    ])
                    ->withInput();
            }

            // Pastikan freelancer yang dilaporkan benar
            $freelancerId = optional($penawaran->freelancer)->id;

            if (
                $request->filled('reported_user_id') &&
                (int) $request->reported_user_id !== $freelancerId
            ) {
                return back()
                    ->withErrors([
                        'reported_user_id' => 'Pengguna yang dilaporkan tidak sesuai dengan penawaran.'
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
            ->route('company.reports.index')
            ->with(
                'success',
                'Laporan berhasil dikirim. Admin akan segera meninjau laporan Anda.'
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
        ]);

        return view('company.reports.show', compact('report'));
    }
}