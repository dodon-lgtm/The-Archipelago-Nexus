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
     *
     * Alur ini KHUSUS untuk General/Website Report di /reports/create.
     * Backend memaksa target = website; konteks lain (workspace_id,
     * project_id, penawaran_id, reported_user_id) DIABAIKAN agar user tidak
     * bisa mengubah /reports/create menjadi company/freelancer/project report.
     */
    public function store(ReportStoreRequest $request)
    {
        // Hanya ambil field yang relevan untuk Website report.
        $data = $request->only(['subject', 'description', 'category', 'attachments']);

        try {
            $this->reportService->store($data);
        } catch (ValidationException $e) {
            // Laporan ditolak (mis. duplikat) -> tampilkan pesan yang jelas.
            return Redirect::back()
                ->withErrors($e->errors())
                ->withInput();
        }

        // Redirect ke daftar laporan sesuai role agar flash success terlihat.
        $indexRoute = Auth::user()->role === 'company'
            ? 'company.reports.index'
            : 'freelancer.reports.index';

        return redirect()
            ->route($indexRoute)
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

// Redirect ke halaman detail laporan sesuai role agar flash terlihat.
        $showRoute = Auth::user()->role === 'company'
            ? 'company.reports.show'
            : 'freelancer.reports.show';

        return redirect()
            ->route($showRoute, $report)
            ->with('success', 'Bukti tambahan berhasil diunggah. Laporan Anda kini kembali ditinjau oleh Admin.');
    }
}
