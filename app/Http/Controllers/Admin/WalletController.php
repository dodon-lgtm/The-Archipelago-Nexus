<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletLedger;
use App\Services\AdminWalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * Halaman Admin Wallet — statistik platform + riwayat ledger + form expense.
     *
     * Saldo dihitung dari wallet_ledger (user_id IS NULL):
     *   saldo = debet - kredit (credit = income, debit = expense)
     */
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');

        $query = WalletLedger::whereNull('user_id')->latest();

        if ($filter === 'income') {
            $query->where('direction', WalletLedger::DIRECTION_CREDIT);
        } elseif ($filter === 'expense') {
            $query->where('direction', WalletLedger::DIRECTION_DEBIT);
        }

        $ledgers = $query->paginate(20)->withQueryString();

        return view('admin.wallet.index', compact('ledgers', 'filter'));
    }

    /**
     * Catat pengeluaran (expense) platform.
     */
    public function storeExpense(Request $request): RedirectResponse
    {
        $request->validate([
            'amount'    => ['required', 'numeric', 'min:100', 'max:1000000000'],
            'description' => ['required', 'string', 'max:2000'],
        ], [
            'amount.required' => 'Nominal pengeluaran wajib diisi.',
            'amount.numeric' => 'Nominal harus berupa angka.',
            'amount.min' => 'Nominal minimal Rp 100.',
            'amount.max' => 'Nominal terlalu besar.',
            'description.required' => 'Deskripsi pengeluaran wajib diisi.',
            'description.max' => 'Deskripsi maksimal 2000 karakter.',
        ]);

        $amount = (float) $request->input('amount');

        AdminWalletService::recordExpense(
            amount: $amount,
            description: $request->input('description'),
            createdBy: auth()->id(),
        );

                return redirect()
            ->route('admin.wallet.index')
            ->with('success', 'Pengeluaran platform berhasil dicatat sebesar Rp ' . number_format($amount, 0, ',', '.'));
    }

    /**
     * Tarik Saldo Admin (SIMULASI/DEMO) — dipanggil via fetch dari Dashboard.
     *
     * Semua angka finansial divalidasi server-side:
     *  - nominal dinormalisasi dari format Rupiah ("50.000" → 50000)
     *  - saldo terakhir diambil dari AdminWalletService::balance()
     *  - pengecekan saldo cukup dilakukan LAGI di dalam transaction
     *    (AdminWalletService::recordAdminWithdrawal) agar saldo tak bisa negatif.
     *
     * TIDAK membuat tabel withdrawal baru — hanya debit pada wallet_ledger.
     */
    public function withdraw(Request $request): JsonResponse
    {
        // Normalisasi format Rupiah sebelum validasi
        if ($request->has('amount')) {
            $request->merge([
                'amount' => preg_replace('/[^\d]/', '', (string) $request->input('amount')),
            ]);
        }

        $validated = $request->validate([
            'amount'         => ['required', 'numeric', 'min:1', 'max:' . AdminWalletService::balance()],
            'method'         => ['required', Rule::in(['bank', 'ewallet'])],
            'account_name'   => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:30'],
        ], [
            'amount.required'  => 'Nominal penarikan wajib diisi.',
            'amount.numeric'   => 'Nominal harus berupa angka.',
            'amount.min'       => 'Nominal harus lebih besar dari nol.',
            'amount.max'       => 'Nominal tidak boleh melebihi saldo tersedia.',
            'method.required'  => 'Metode penarikan wajib dipilih.',
            'method.in'        => 'Metode penarikan tidak valid.',
            'account_name.required'  => 'Nama pemilik rekening wajib diisi.',
            'account_number.required' => 'Nomor rekening/e-wallet wajib diisi.',
            'account_number.max'      => 'Nomor rekening/e-wallet terlalu panjang.',
        ]);

        $amount = (float) $validated['amount'];

        $result = AdminWalletService::recordAdminWithdrawal(
            amount: $amount,
            method: $validated['method'],
            accountName: $validated['account_name'],
            accountNumber: $validated['account_number'],
            createdBy: auth()->id(),
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'balance' => AdminWalletService::balance(),
            ], 422);
        }

        return response()->json([
            'success'          => true,
            'message'          => $result['message'],
            'balance'          => AdminWalletService::balance(),
            'withdrawn_amount' => $amount,
        ]);
    }
}