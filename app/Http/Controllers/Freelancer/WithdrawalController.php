<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Freelancer\WithdrawalStoreRequest;
use App\Services\WithdrawalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function __construct(
        protected WithdrawalService $withdrawalService,
    ) {
    }

    /**
     * Ajukan penarikan dana (status awal: menunggu).
     */
    public function store(WithdrawalStoreRequest $request): RedirectResponse
    {
        $withdrawal = $this->withdrawalService->store(
            $request->validated(),
            Auth::id()
        );

        return redirect()
            ->route('freelancer.pendapatan.index')
            ->with('success', 'Penarikan '
                . $withdrawal->withdrawal_code
                . ' berhasil dicairkan (simulasi). Saldo tersedia Anda telah diperbarui.');
    }
}