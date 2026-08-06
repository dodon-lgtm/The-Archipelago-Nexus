<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportEvidenceRequest;
use App\Http\Requests\ReportStoreRequest;
use App\Services\ReportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Show the report creation form (General Report / Website).
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Store a newly created report.
     */
    public function store(ReportStoreRequest $request)
    {
        try {
            $this->reportService->store($request->validated());
        } catch (ValidationException $e) {
            // Laporan ditolak (mis. duplikat) -> tampilkan pesan yang jelas.
            return Redirect::back()
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()
            ->route(Auth::user()->role . '.dashboard')
            ->with('success', 'Laporan berhasil dikirim. Terima kasih telah membantu menjaga kualitas platform. Tim Admin akan meninjau laporan Anda secepat mungkin.');
    }

    /**
     * Unggah bukti tambahan untuk laporan berstatus 'menunggu-bukti'.
     */
    public function uploadEvidence(ReportEvidenceRequest $request, \App\Models\Report $report)
    {
        try {
            $this->reportService->uploadEvidence($report, $request->validated('attachments'));
        } catch (ValidationException $e) {
            return Redirect::back()
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()
            ->route(Auth::user()->role . '.dashboard')
            ->with('success', 'Bukti tambahan berhasil diunggah. Laporan Anda kini kembali ditinjau oleh Admin.');
    }
}
