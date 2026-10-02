<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WithdrawalRejectRequest;
use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    public function __construct(
        protected WithdrawalService $withdrawalService,
    ) {
    }

    /**
     * Daftar seluruh permintaan penarikan FREELANCER (dengan filter status).
     * Penarikan type 'admin' tidak masuk antrean verifikasi ini.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'semua');

        $query = Withdrawal::with(['user', 'processedBy'])
            ->ofType(Withdrawal::TYPE_FREELANCER)
            ->latest();

        if ($status !== 'semua' && in_array($status, Withdrawal::ACTIVE_STATUSES, true)) {
            $query->where('status', $status);
        } elseif (in_array($status, [Withdrawal::STATUS_BERHASIL, Withdrawal::STATUS_DITOLAK], true)) {
            $query->where('status', $status);
        }

        $withdrawals = $query->paginate(15)->withQueryString();

        return view('admin.withdrawals.index', compact('withdrawals', 'status'));
    }

    /**
     * Detail permintaan penarikan.
     */
    public function show(Withdrawal $withdrawal): View
    {
        abort_unless($withdrawal->isFreelancer(), 404);

        $withdrawal->load(['user', 'processedBy']);

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Proses penarikan (menunggu -> diproses).
     */
    public function process(Withdrawal $withdrawal): RedirectResponse
    {
        abort_unless($withdrawal->isFreelancer(), 404);

        try {
            $this->withdrawalService->process($withdrawal, Auth::id());

            return redirect()
                ->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Penarikan ' . $withdrawal->withdrawal_code . ' sedang diproses.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->route('admin.withdrawals.show', $withdrawal)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Setujui penarikan (simulasi pencairan berhasil).
     */
    public function approve(Withdrawal $withdrawal): RedirectResponse
    {
        abort_unless($withdrawal->isFreelancer(), 404);

        try {
            $this->withdrawalService->approve($withdrawal, Auth::id());

            return redirect()
                ->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Penarikan ' . $withdrawal->withdrawal_code . ' berhasil dicairkan (simulasi payout).');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->route('admin.withdrawals.show', $withdrawal)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Tolak penarikan beserta alasan. Saldo freelancer otomatis kembali.
     */
    public function reject(WithdrawalRejectRequest $request, Withdrawal $withdrawal): RedirectResponse
    {
        abort_unless($withdrawal->isFreelancer(), 404);

        try {
            $this->withdrawalService->reject(
                $withdrawal,
                Auth::id(),
                $request->input('rejection_reason')
            );

            return redirect()
                ->route('admin.withdrawals.show', $withdrawal)
                ->with('success', 'Penarikan ' . $withdrawal->withdrawal_code . ' ditolak. Saldo freelancer dikembalikan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->route('admin.withdrawals.show', $withdrawal)
                ->with('error', $e->getMessage());
        }
    }
}