<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Freelancer\WithdrawalStoreRequest;
use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PendapatanController extends Controller
{
    /**
     * Display a listing of the freelancer's revenue/earnings.
     */
    public function index(WithdrawalService $withdrawalService): View
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

        // ── Saldo & riwayat penarikan ──────────────────────────────
        $withdrawals = Withdrawal::with('processedBy')
            ->forUser(Auth::id())
            ->latest()
            ->paginate(10);

        // Saldo tertahan: nominal penarikan yang masih menunggu/diproses.
        $heldBalance = (float) Withdrawal::forUser(Auth::id())
            ->active()
            ->sum('amount');

        // Saldo tersedia: total pendapatan - tertahan - sudah ditarik.
        $withdrawnBalance = (float) Withdrawal::forUser(Auth::id())
            ->where('status', Withdrawal::STATUS_BERHASIL)
            ->sum('amount');

        $availableBalance = max(0.0, (float) $totalEarned - $heldBalance - $withdrawnBalance);

        $minWithdraw = WithdrawalStoreRequest::MIN_WITHDRAW;

        return view('freelancer.pendapatan.index', compact(
            'payments',
            'totalEarned',
            'totalPending',
            'withdrawals',
            'availableBalance',
            'heldBalance',
            'withdrawnBalance',
            'minWithdraw'
        ));
    }
}

