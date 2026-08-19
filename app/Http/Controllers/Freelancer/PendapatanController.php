<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PendapatanController extends Controller
{
    /**
     * Display a listing of the freelancer's revenue/earnings.
     *
     * Pendapatan hanya dihitung dari dana yang SUDAH DIRELEASE (funds_status
     * released / released_partial). Payment yang hanya berstatus 'paid' tetapi
     * masih HELD belum menjadi pendapatan yang dapat ditarik.
     */
    public function index(): View
    {
        $payments = Payment::with([
            'workspace.project',
            'company',
        ])
            ->where('freelancer_id', Auth::id())
            ->latest()
            ->paginate(15);

        // Saldo tersedia = dana yang sudah dirilis (release penuh / sebagian)
        $totalEarned = Payment::where('freelancer_id', Auth::id())
            ->whereIn('funds_status', [Payment::FUNDS_RELEASED, Payment::FUNDS_RELEASED_PARTIAL])
            ->sum('released_amount');

        // Dana tertahan = sudah dibayar company tetapi belum dirilis (held / disputed)
        $totalHeld = Payment::where('freelancer_id', Auth::id())
            ->whereIn('funds_status', [Payment::FUNDS_HELD, Payment::FUNDS_DISPUTED])
            ->sum('freelancer_receive');

        // Menunggu pembayaran = belum dibayar company
        $totalPending = Payment::where('freelancer_id', Auth::id())
            ->whereIn('status', ['pending', 'waiting_verification'])
            ->sum('freelancer_receive');

        // Dana yang direfund ke company (informasi)
        $totalRefunded = Payment::where('freelancer_id', Auth::id())
            ->whereIn('funds_status', [Payment::FUNDS_REFUNDED, Payment::FUNDS_REFUNDED_PARTIAL])
            ->sum('refunded_amount');

        return view('freelancer.pendapatan.index', compact(
            'payments',
            'totalEarned',
            'totalHeld',
            'totalPending',
            'totalRefunded'
        ));
    }
}

