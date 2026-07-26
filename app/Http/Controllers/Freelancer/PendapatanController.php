<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PendapatanController extends Controller
{
    /**
     * Display a listing of the freelancer's revenue/earnings.
     */
    public function index(): View
    {
        $payments = \App\Models\Payment::with([
            'workspace.project',
            'company',
        ])
            ->where('freelancer_id', Auth::id())
            ->latest()
            ->paginate(15);

        $totalEarned = \App\Models\Payment::where('freelancer_id', Auth::id())
            ->where('status', 'paid')
            ->sum('freelancer_receive');

        $totalPending = \App\Models\Payment::where('freelancer_id', Auth::id())
            ->whereIn('status', ['pending', 'waiting_verification'])
            ->sum('freelancer_receive');

        return view('freelancer.pendapatan.index', compact(
            'payments',
            'totalEarned',
            'totalPending'
        ));
    }
}

