<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\WalletLedger;
use App\Models\Withdrawal;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


/**
 * AdminWalletService — PUSAT pencatatan pendapatan & pengeluaran Platform (Admin Wallet).
 *
 * TIDAK ada kolom saldo terpisah. Saldo Admin SELALU dihitung dari wallet_ledger:
 *     saldo = SUM(credit) − SUM(debit)  untuk seluruh baris platform (user_id IS NULL).
 *
 * Idempotensi (SANGAT PENTING):
 *   - Sebelum insert dicek apakah sumber (payment_id / withdrawal_id + type) sudah ada ledger.
 *   - Unique index (payment_id, type) & (withdrawal_id, type) mengamankan race condition.
 */
class AdminWalletService
{
    /** Harga per proyek tambahan (kuota) — DITENTUKAN SERVER, tidak pernah percaya input. Fallback default. */
    public const QUOTA_PRICE = 10000;

    /** Harga upload proyek dari Financial Settings (fallback ke konstanta). */
    public static function quotaPrice(): float
    {
        $setting = \App\Models\FinancialSetting::getSettings();

        return max(0.0, $setting->paidUploadPrice());
    }

    // Kolom wallet_ledger.source
    public const SOURCE_QUOTA_PAYMENT  = 'quota_payment';
    public const SOURCE_WITHDRAWAL_FEE = 'withdrawal_fee';
        public const SOURCE_ADMIN_EXPENSE  = 'admin_expense';
    public const SOURCE_ADMIN_WITHDRAWAL = 'admin_withdrawal';

    /** Catat pendapatan biaya kuota proyek tambahan setelah Payment kuota 'paid'. Idempotent. */
    public static function recordProjectQuotaIncome(Payment $payment, ?int $createdBy = null): ?WalletLedger
    {
        if ((float) $payment->amount <= 0) {
            return null;
        }

        return static::record(
            type: WalletLedger::TYPE_PROJECT_QUOTA_FEE,
            amount: (float) $payment->amount,
            description: 'Pendapatan biaya kuota proyek tambahan (Rp '
                . number_format((float) $payment->amount, 0, ',', '.') . '/proyek). Invoice: ' . $payment->invoice_number,
            direction: WalletLedger::DIRECTION_CREDIT,
            paymentId: $payment->id,
            withdrawalId: null,
            source: self::SOURCE_QUOTA_PAYMENT,
            createdBy: $createdBy,
        );
    }

    /** Catat fee withdrawal freelancer (5%) sebagai INCOME Platform. Idempotent per withdrawal. */
    public static function recordWithdrawalFee(Withdrawal $withdrawal, ?int $createdBy = null): ?WalletLedger
    {
        if ((float) $withdrawal->fee <= 0) {
            return null;
        }

        return static::record(
            type: WalletLedger::TYPE_WITHDRAWAL_FEE,
            amount: (float) $withdrawal->fee,
            description: 'Fee withdrawal 5% — ' . $withdrawal->withdrawal_code
                . ' (penarikan Rp ' . number_format((float) $withdrawal->amount, 0, ',', '.')
                . ', fee Rp ' . number_format((float) $withdrawal->fee, 0, ',', '.') . ').',
            direction: WalletLedger::DIRECTION_CREDIT,
            paymentId: null,
            withdrawalId: $withdrawal->id,
            source: self::SOURCE_WITHDRAWAL_FEE,
            createdBy: $createdBy,
        );
    }

    /** Catat PENGELUARAN platform/admin sebagai DEBIT. Saldo mengikuti perhitungan ledger. */
    public static function recordExpense(
        float $amount,
        string $description,
        ?string $date = null,
        ?int $createdBy = null,
        ?array $meta = null,
    ): ?WalletLedger {
        if ($amount <= 0) {
            return null;
        }

        $ledger = static::record(
            type: WalletLedger::TYPE_ADMIN_EXPENSE,
            amount: $amount,
            description: $description,
            direction: WalletLedger::DIRECTION_DEBIT,
            paymentId: null,
            withdrawalId: null,
            source: self::SOURCE_ADMIN_EXPENSE,
            createdBy: $createdBy,
            meta: $meta,
        );

        if ($ledger && $date && strtotime($date) !== false) {
            $ledger->forceFill([
                'created_at' => date('Y-m-d H:i:s', strtotime($date)),
                'updated_at' => now(),
            ])->save();
        }

                return $ledger;
    }

    /**
     * Buat PENARIKAN SALDO ADMIN — mengikuti pola withdrawal Freelancer.
     *
     * Reuse tabel `withdrawals` (withdrawal_type='admin') sehingga:
     *   - ada entity withdrawal berkode unik untuk history & audit;
     *   - ledger debit selalu terikat `withdrawal_id` → unique
     *     (withdrawal_id, type) membuat idempotensi BENAR-BENAR bekerja
     *     (root cause bug "Gagal mencatat penarikan" adalah exists-check
     *     tanpa filter ketika kedua id null).
     *
     * Aturan dana:
     *   - fee = BIAYA PROVIDER dari config/withdrawal.php (bukan platform fee);
     *   - TIDAK ada platform fee 5% untuk admin;
     *   - debit wallet = amount PENUH; admin menerima = amount − fee provider;
     *   - saldo dihitung ulang DI DALAM transaction + lockForUpdate baris
     *     platform agar penarikan paralel tak bisa lolos validasi bersamaan.
     */
    public static function recordAdminWithdrawal(
        float $amount,
        string $method,
        string $bankName,
        string $accountName,
        string $accountNumber,
        ?int $createdBy = null,
    ): array {
        if ($amount <= 0) {
            return ['success' => false, 'message' => 'Nominal penarikan tidak valid.', 'withdrawal' => null];
        }

        if (!in_array($method, \App\Services\WithdrawalService::providerMethods(), true)) {
            return ['success' => false, 'message' => 'Metode/provider penarikan tidak valid.', 'withdrawal' => null];
        }

        return DB::transaction(function () use ($amount, $method, $bankName, $accountName, $accountNumber, $createdBy) {
            // Kunci seluruh baris platform (user_id NULL) agar dua request
            // paralel tidak sama-sama lolos validasi saldo.
            DB::table('wallet_ledger')
                ->whereNull('user_id')
                ->lockForUpdate()
                ->get();

            $balance = self::balance();

            if ($amount > $balance) {
                return [
                    'success' => false,
                    'message' => 'Saldo Admin tidak cukup. Saldo tersedia: Rp '
                        . number_format($balance, 0, ',', '.'),
                    'withdrawal'  => null,
                ];
            }

            return static::finalizeAdminWithdrawal($amount, $method, $bankName, $accountName, $accountNumber, $createdBy);
        });
    }

    /**
     * Tahap akhir (dalam transaction): buat entity Withdrawal + debit ledger.
     * Tax rate 5% TIDAK diterapkan — hanya fee provider dari config.
     */
    protected static function finalizeAdminWithdrawal(
        float $amount,
        string $method,
        string $bankName,
        string $accountName,
        string $accountNumber,
        ?int $createdBy,
    ): array {
        $providerFee = \App\Services\WithdrawalService::calculateProviderFee($method, $amount);
        $received    = round($amount - $providerFee, 2);

        // 1) Entity Withdrawal (status langsung BERHASIL — simulasi payout,
        //    konsisten dengan flow Freelancer existing).
        $withdrawal = Withdrawal::create([
            'withdrawal_code' => 'WDA-TMP-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(10)),
            'withdrawal_type' => Withdrawal::TYPE_ADMIN,
            'user_id'         => $createdBy ?? Auth::id(),
            'amount'          => $amount,          // total dipotong dari wallet
            'fee'             => $providerFee,      // fee PROVIDER (eksternal)
            'net_amount'      => $received,         // diterima admin
            'method'          => $method,
            'bank_name'       => $bankName,
            'account_name'    => $accountName,
            'account_number'  => $accountNumber,
            'status'          => Withdrawal::STATUS_BERHASIL,
            'processed_by'    => $createdBy ?? Auth::id(),
            'processed_at'    => now(),
            'paid_at'         => now(),
        ]);

        // Kode final deterministik WD-ADMIN-00001.
        $withdrawal->update([
            'withdrawal_code' => 'WD-ADMIN-' . str_pad((string) $withdrawal->id, 5, '0', STR_PAD_LEFT),
        ]);

        // 2) Debit ledger = AMOUNT PENUH, terikat withdrawal_id
        //    → unique (withdrawal_id, type) menjamin tak ada debit ganda.
        $ledger = static::record(
            type: WalletLedger::TYPE_ADMIN_WITHDRAWAL,
            amount: $amount,
            description: 'Penarikan Saldo Admin ' . $withdrawal->withdrawal_code
                . ' via ' . \App\Services\WithdrawalService::providerLabel($method)
                . ' (' . $bankName . ', a/n ' . $accountName . ' ****' . substr($accountNumber, -4) . ').'
                . ' Fee provider Rp ' . number_format($providerFee, 0, ',', '.')
                . '; diterima Rp ' . number_format($received, 0, ',', '.') . '.',
            direction: WalletLedger::DIRECTION_DEBIT,
            paymentId: null,
            withdrawalId: $withdrawal->id,
            source: self::SOURCE_ADMIN_WITHDRAWAL,
            createdBy: $createdBy,
            meta: [
                'method'         => $method,
                'method_label'   => \App\Services\WithdrawalService::providerLabel($method),
                'bank_name'      => $bankName,
                'account_name'   => $accountName,
                'account_number' => $accountNumber,
                'provider_fee'   => $providerFee,
                'received'       => $received,
            ],
        );

        if (!$ledger) {
            throw new \RuntimeException('Gagal mencatat ledger penarikan admin.');
        }

        return [
            'success'    => true,
            'message'    => 'Penarikan ' . $withdrawal->withdrawal_code . ' berhasil. Debit Rp '
                . number_format($amount, 0, ',', '.') . ' (fee provider Rp '
                . number_format($providerFee, 0, ',', '.') . '), diterima Rp '
                . number_format($received, 0, ',', '.') . '.',
            'withdrawal' => $withdrawal->fresh(),
            'fee'        => $providerFee,
            'received'   => $received,
            'balance'    => self::balance(),
        ];
    }

    /** Riwayat penarikan saldo admin (dari tabel withdrawals, type=admin). */
    public static function adminWithdrawalHistory(int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Withdrawal::query()
            ->ofType(Withdrawal::TYPE_ADMIN)
            ->with('processedBy:id,name')
            ->latest()
            ->paginate($perPage, ['*'], 'withdraw_page');
    }

    // ─── STATISTIK SALDO (dihitung dari ledger, BUKAN kolom manual) ───

    /** Saldo Admin saat ini = total income − total expense. */
    public static function balance(): float
    {
        return round(self::totalIncome() - self::totalExpense(), 2);
    }

    /** Total pendapatan (credit) platform. */
    public static function totalIncome(): float
    {
        return (float) WalletLedger::whereNull('user_id')
            ->where('direction', WalletLedger::DIRECTION_CREDIT)
            ->sum('amount');
    }

    /** Total pengeluaran (debit) platform. */
    public static function totalExpense(): float
    {
        return (float) WalletLedger::whereNull('user_id')
            ->where('direction', WalletLedger::DIRECTION_DEBIT)
            ->sum('amount');
    }

    /**
     * Pendapatan (In) platform per bulan.
     *
     * @param string|null $month Bulan target format "YYYY-MM" (contoh: "2026-09").
     *                           Jika null/kosong → bulan & tahun berjalan (now()).
     *                           Jika tidak ada transaksi pada bulan tsb → 0.
     */
    public static function monthlyIncome(?string $month = null): float
    {
        [$year, $mon] = static::monthParts($month);

        return (float) WalletLedger::whereNull('user_id')
            ->where('direction', WalletLedger::DIRECTION_CREDIT)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $mon)
            ->sum('amount');
    }

    /**
     * Pengeluaran (Out) platform per bulan.
     *
     * @param string|null $month Bulan target format "YYYY-MM" (contoh: "2026-09").
     *                           Jika null/kosong → bulan & tahun berjalan (now()).
     *                           Jika tidak ada transaksi pada bulan tsb → 0.
     */
    public static function monthlyExpense(?string $month = null): float
    {
        [$year, $mon] = static::monthParts($month);

        return (float) WalletLedger::whereNull('user_id')
            ->where('direction', WalletLedger::DIRECTION_DEBIT)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $mon)
            ->sum('amount');
    }

    // ──────────────────────────────────────────────────────────────────
    // INTERNAL
    // ──────────────────────────────────────────────────────────────────

    /**
     * Pecah nilai "YYYY-MM" menjadi pasangan [tahun, bulan] (int).
     *
     * Fallback aman: bila $month null, kosong, atau format tidak valid
     * → gunakan bulan & tahun berjalan (now()).
     *
     * @return array{0:int,1:int}
     */
    protected static function monthParts(?string $month): array
    {
        if ($month !== null && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month) === 1) {
            return [(int) substr($month, 0, 4), (int) substr($month, 5, 2)];
        }

        return [(int) now()->format('Y'), (int) now()->format('m')];
    }

    protected static function record(
        string $type,
        float $amount,
        string $description,
        string $direction,
        ?int $paymentId,
        ?int $withdrawalId,
        string $source,
        ?int $createdBy,
        ?array $meta = null,
    ): ?WalletLedger {
        if ($amount <= 0) {
            return null;
        }

        return DB::transaction(function () use ($type, $amount, $description, $direction, $paymentId, $withdrawalId, $source, $createdBy, $meta) {
            $exists = WalletLedger::query()
                ->when($paymentId !== null, fn ($q) => $q->where('payment_id', $paymentId))
                ->when($withdrawalId !== null, fn ($q) => $q->where('withdrawal_id', $withdrawalId))
                ->where('type', $type)
                ->exists();

            if ($exists) {
                return null; // sudah tercatat — idempotent
            }

            try {
                return WalletLedger::create([
                    'user_id' => null, // user_id NULL = pemilik Platform/Admin wallet
                    'workspace_id' => null,
                    'payment_id' => $paymentId,
                    'withdrawal_id' => $withdrawalId,
                    'type' => $type,
                    'source' => $source,
                    'amount' => $amount,
                    'direction' => $direction,
                    'balance_after' => self::balanceAfter(
                        $direction === WalletLedger::DIRECTION_CREDIT ? $amount : -$amount
                    ),
                    'description' => $description,
                    'meta' => $meta,
                    'created_by' => $createdBy,
                ]);
            } catch (QueryException $e) {
                // Duplicate entry (unique index) dari race condition → aman diabaikan.
                if (($e->errorInfo[1] ?? null) === 1062) {
                    return null;
                }

                throw $e;
            }
        });
    }

    /** Saldo platform setelah delta (untuk kolom balance_after). */
    protected static function balanceAfter(float $delta): float
    {
        return round(self::balance() + $delta, 2);
    }
}