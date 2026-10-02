<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletLedger;
use App\Services\AdminWalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /** Kategori pengeluaran platform (disimpan terstruktur di wallet_ledger.meta). */
    public const EXPENSE_CATEGORIES = [
        'provider'    => 'Pembayaran Provider',
        'operasional' => 'Biaya Operasional',
        'refund'      => 'Refund',
        'platform'    => 'Pengeluaran Platform',
        'penyesuaian' => 'Penyesuaian Saldo',
        'lainnya'     => 'Lainnya',
    ];

    /**
     * Filter riwayat yang didukung halaman Admin Wallet.
     * Semua nilai dipetakan ke kolom/type EXISTING wallet_ledger — tidak ada enum baru.
     */
    public const HISTORY_FILTERS = [
        'all',
        'income',
        'expense',
        'withdrawal_fee',
        'quota_fee',
        'platform_fee',
        'admin_expense',
        'admin_withdrawal',
    ];

    /** Map filter → type kolom wallet_ledger existing. */
    protected const FILTER_TYPE_MAP = [
        'withdrawal_fee'   => WalletLedger::TYPE_WITHDRAWAL_FEE,
        'quota_fee'        => WalletLedger::TYPE_PROJECT_QUOTA_FEE,
        'platform_fee'     => WalletLedger::TYPE_FEE_EARNED,
        'admin_expense'    => WalletLedger::TYPE_ADMIN_EXPENSE,
        'admin_withdrawal' => WalletLedger::TYPE_ADMIN_WITHDRAWAL,
    ];

    /**
     * Halaman Admin Wallet — statistik platform + riwayat ledger + form expense
     * + form & riwayat Tarik Saldo.
     *
     * Saldo dihitung dari wallet_ledger (user_id IS NULL):
     *   saldo = SUM(credit) − SUM(debit)
     */
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');
        if (!in_array($filter, self::HISTORY_FILTERS, true)) {
            $filter = 'all';
        }

        $search = trim((string) $request->query('q', ''));

        // Parameter filter bulan (format YYYY-MM, contoh: "2026-09").
        $month = $request->get('month');

        $query = WalletLedger::whereNull('user_id')
            ->with(['payment:id,invoice_number', 'withdrawal:id,withdrawal_code'])
            ->latest();

        // ── Filter jenis transaksi ──
        if ($filter === 'income') {
            $query->where('direction', WalletLedger::DIRECTION_CREDIT);
        } elseif ($filter === 'expense') {
            $query->where('direction', WalletLedger::DIRECTION_DEBIT);
        } elseif (isset(self::FILTER_TYPE_MAP[$filter])) {
            $query->where('type', self::FILTER_TYPE_MAP[$filter]);
        }

        // ── Filter bulan (data Tabel Kas menyesuaikan bulan yang dipilih) ──
        if ($month !== null && is_string($month) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month) === 1) {
            $query
                ->whereYear('created_at', (int) substr($month, 0, 4))
                ->whereMonth('created_at', (int) substr($month, 5, 2));
        }

        // ── Pencarian (deskripsi / kode payment / kode withdrawal) ──
        if ($search !== '') {
            $query->where(function ($w) use ($search) {
                $w->where('description', 'like', "%{$search}%")
                    ->orWhereHas('payment', fn($p) => $p->where('invoice_number', 'like', "%{$search}%"))
                    ->orWhereHas('withdrawal', fn($d) => $d->where('withdrawal_code', 'like', "%{$search}%"));

                if (ctype_digit($search)) {
                    $w->orWhere('id', (int) $search);
                }
            });
        }

        $ledgers = $query->paginate(20)->withQueryString();

        // Riwayat penarikan saldo admin (pagination terpisah).
        $adminWithdrawals = AdminWalletService::adminWithdrawalHistory(10);

        // Ledger debit admin_withdrawal per withdrawal pada halaman ini
        // (satu query) — sumber kolom Saldo Sebelum/Sesudah di view.
        $withdrawalLedgers = WalletLedger::query()
            ->where('type', WalletLedger::TYPE_ADMIN_WITHDRAWAL)
            ->whereIn('withdrawal_id', collect($adminWithdrawals->items() ?? $adminWithdrawals)->pluck('id'))
            ->get()
            ->keyBy('withdrawal_id');
        return view('admin.wallet.index', [
            'ledgers'           => $ledgers,
            'filter'            => $filter,
            'search'            => $search,
            'month'             => $month,
            'categories'        => self::EXPENSE_CATEGORIES,
            'filters'           => self::HISTORY_FILTERS,
            'adminWithdrawals'  => $adminWithdrawals,
            'withdrawalLedgers' => $withdrawalLedgers,
            'balance'           => AdminWalletService::balance(),
            'txExpense'         => $this->issueTxToken('expense'),
            'txWithdraw'        => $this->issueTxToken('withdraw'),
        ]);
    }

    /**
     * Catat pengeluaran (expense) platform.
     */
    public function storeExpense(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount'       => ['required', 'numeric', 'min:100', 'max:1000000000'],
            'description'  => ['required', 'string', 'max:2000'],
            'category'     => ['required', 'string', Rule::in(array_keys(self::EXPENSE_CATEGORIES))],
            'expense_date' => ['nullable', 'date', 'before_or_equal:today'],
            '_tx'          => ['required', 'string'],
        ], [
            'amount.required'              => 'Nominal pengeluaran wajib diisi.',
            'amount.numeric'               => 'Nominal harus berupa angka.',
            'amount.min'                   => 'Nominal minimal Rp 100.',
            'amount.max'                   => 'Nominal terlalu besar.',
            'description.required'         => 'Deskripsi pengeluaran wajib diisi.',
            'description.max'              => 'Deskripsi maksimal 2000 karakter.',
            'category.required'            => 'Kategori pengeluaran wajib dipilih.',
            'category.in'                  => 'Kategori pengeluaran tidak valid.',
            'expense_date.date'            => 'Tanggal pengeluaran tidak valid.',
            'expense_date.before_or_equal' => 'Tanggal pengeluaran tidak boleh di masa depan.',
        ]);

        // Proteksi double-submit / replay (server-side, one-time token).
        if (!$this->consumeTxToken('expense', (string) $request->input('_tx'))) {
            return redirect()
                ->route('admin.wallet.index')
                ->with('error', 'Transaksi sudah diproses sebelumnya atau sesi kedaluwarsa. Silakan muat ulang halaman.');
        }

        $amount = (float) $validated['amount'];

        $ledger = AdminWalletService::recordExpense(
            amount: $amount,
            description: $validated['description'],
            date: $validated['expense_date'] ?? null,
            createdBy: Auth::id(),
            meta: [
                'category'       => $validated['category'],
                'category_label' => self::EXPENSE_CATEGORIES[$validated['category']],
                'source_date'    => $validated['expense_date'] ?? null,
            ],
        );

        if (!$ledger) {
            return redirect()
                ->route('admin.wallet.index')
                ->with('error', 'Gagal mencatat pengeluaran. Nominal harus lebih besar dari nol.');
        }

        return redirect()
            ->route('admin.wallet.index')
            ->with('success', 'Pengeluaran platform berhasil dicatat sebesar Rp ' . number_format($amount, 0, ',', '.'));
    }

    /**
     * Tarik Saldo Admin — TANPA biaya platform dari aplikasi:
     * nominal ditarik = nominal diterima. Debit langsung dicatat pada
     * wallet_ledger (user_id NULL) via AdminWalletService (idempotent +
     * re-validasi saldo dalam transaction).
     *
     * Respons dual-mode: JSON untuk pemanggil fetch/AJAX lama,
     * redirect+flash untuk form POST biasa (PRG — refresh aman).
     *
     * Semua angka finansial divalidasi server-side; saldo diambil dari
     * AdminWalletService::balance() (computed), BUKAN dari frontend.
     */
    public function withdraw(Request $request): JsonResponse|RedirectResponse
    {
        // Normalisasi format Rupiah sebelum validasi ("1.500.000" → "1500000").
        if ($request->has('amount')) {
            $request->merge([
                'amount' => preg_replace('/[^\d]/', '', (string) $request->input('amount')),
            ]);
        }

        $providerMethods = \App\Services\WithdrawalService::providerMethods();
        $minAmount       = (int) config('withdrawal.min_amount', 10000);

        $validated = $request->validate([
            'amount'         => ['required', 'numeric', 'min:' . $minAmount, 'max:' . AdminWalletService::balance()],
            'method'         => ['required', Rule::in($providerMethods)],
            'bank_name'      => ['required', 'string', 'max:100'],
            'account_name'   => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:30'],
            '_tx'            => ['required', 'string'],
        ], [
            'amount.required'  => 'Nominal penarikan wajib diisi.',
            'amount.numeric'   => 'Nominal harus berupa angka.',
            'amount.min'       => 'Nominal penarikan minimal Rp ' . number_format($minAmount, 0, ',', '.') . '.',
            'amount.max'       => 'Nominal tidak boleh melebihi saldo tersedia.',
            'method.required'  => 'Metode penarikan wajib dipilih.',
            'method.in'        => 'Metode penarikan tidak valid.',
            'bank_name.required'      => 'Nama bank/e-wallet wajib diisi.',
            'account_name.required'   => 'Nama pemilik rekening wajib diisi.',
            'account_number.required' => 'Nomor rekening/e-wallet wajib diisi.',
            'account_number.max'      => 'Nomor rekening/e-wallet terlalu panjang.',
        ]);

        // Proteksi double-submit / replay (server-side, one-time token).
        if (!$this->consumeTxToken('withdraw', (string) $request->input('_tx'))) {
            $message = 'Penarikan sudah diproses sebelumnya atau sesi kedaluwarsa. Silakan muat ulang halaman.';

            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 409)
                : back()->with('error', $message);
        }

        $amount = (float) $validated['amount'];

        $result = AdminWalletService::recordAdminWithdrawal(
            amount: $amount,
            method: $validated['method'],
            bankName: $validated['bank_name'],
            accountName: $validated['account_name'],
            accountNumber: $validated['account_number'],
            createdBy: Auth::id(),
        );

        if (!$result['success']) {
            return $request->expectsJson()
                ? response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'balance' => AdminWalletService::balance(),
                ], 422)
                : back()->with('error', $result['message']);
        }

        /** @var \App\Models\Withdrawal $withdrawal */
        $withdrawal = $result['withdrawal'];

        return $request->expectsJson()
            ? response()->json([
                'success'          => true,
                'message'          => $result['message'],
                'balance'          => $result['balance'],
                'withdrawn_amount' => $amount,
                'provider_fee'     => $result['fee'],
                'received'         => $result['received'],
                'code'             => $withdrawal->withdrawal_code,
            ])
            : redirect()
            ->route('admin.wallet.index')
            ->with('success', $result['message']);
    }

    // ─── PROTEKSI DOUBLE-SUBMIT (one-time transaction token) ──────────

    /**
     * Terbitkan token transaksi sekali pakai untuk satu form.
     * Disimpan di Cache agar kedaluwarsa otomatis dan bekerja lintas tab.
     */
    protected function issueTxToken(string $action): string
    {
        $token = Str::random(40);

        Cache::put(
            key: 'wallet.tx.' . $action . '.' . Auth::id() . '.' . $token,
            value: true,
            ttl: now()->addMinutes(30),
        );

        return $token;
    }

    /**
     * Konsumsi token transaksi. Mengembalikan false bila token tidak ada /
     * sudah pernah dipakai (double submit, refresh resubmit, replay).
     */
    protected function consumeTxToken(string $action, string $token): bool
    {
        return Cache::pull('wallet.tx.' . $action . '.' . Auth::id() . '.' . $token) === true;
    }
}
