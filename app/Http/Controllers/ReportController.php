<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Show the report creation form.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created report.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject'          => 'required|string|max:255',
            'description'      => 'required|string|max:5000',
            'reported_user_id' => 'nullable|exists:users,id',
            'project_id'       => 'nullable|exists:projects,id',
        ]);

        $report = Report::create([
            'reporter_id'      => Auth::id(),
            'reported_user_id' => $validated['reported_user_id'] ?? null,
            'project_id'       => $validated['project_id'] ?? null,
            'subject'          => $validated['subject'],
            'description'      => $validated['description'],
            'status'           => 'menunggu',
        ]);

        // Kirim notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $this->notificationService->createNotification(
                $admin->id,
                'Laporan Baru: ' . $report->subject,
                $report->description,
                'company_request.created',
                route('admin.reports.show', $report),
                ['report_id' => $report->id]
            );
        }

        return redirect()
            ->route(Auth::user()->role . '.dashboard')
            ->with('success', 'Laporan berhasil dikirim. Kami akan memproses laporan Anda segera.');
    }
}
