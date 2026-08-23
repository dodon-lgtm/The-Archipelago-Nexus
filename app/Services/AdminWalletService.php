<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\WalletLedger;
use App\Models\Withdrawal;
use Illuminate\Database\QueryException;
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
    /** Harga per proyek tambahan (kuota) — DITENTUKAN SERVER, tidak pernah percaya input. */
    public const QUOTA_PRICE = 10000;

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
            description: 'Pendapatan biaya kuota proyek tambahan (Rp 10.000/proyek). Invoice: ' . $payment->invoice_number,
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
     * Catat PENARIKAN SALDO ADMIN (simulasi/demo) sebagai DEBIT pada ledger platform.
     *
     * Security server-side:
     *  - DB::transaction + lockForUpdate pada seluruh baris platform agar
     *    penarikan bersamaan terserialisasi.
     *  - Saldo dihitung ULANG di dalam transaction; debit DITOLAK bila
     *    nominal melebihi saldo (saldo tidak boleh negatif).
     *  - balance_after dihitung melalui mekanisme ledger yang sama dengan
     *    income/expense existing.
     */
    public static function recordAdminWithdrawal(
        float $amount,
        string $method,
        string $accountName,
        string $accountNumber,
        ?int $createdBy = null,
    ): array {
        if ($amount <= 0) {
            return ['success' => false, 'message' => 'Nominal penarikan tidak valid.', 'ledger' => null];
        }

        return DB::transaction(function () use ($amount, $method, $accountName, $accountNumber, $createdBy) {
            // Kunci baris platform (user_id NULL) supaya penarikan paralel tidak
            // bisa sama-sama lolos validasi saldo.
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
                    'ledger'  => null,
                ];
            }

            $methodLabel = $method === 'bank' ? 'Bank' : 'E-Wallet';

            $ledger = static::record(
                type: WalletLedger::TYPE_ADMIN_WITHDRAWAL,
                amount: $amount,
                description: 'Penarikan Saldo Admin via ' . $methodLabel . ' — '
                    . $accountName . ' (' . $accountNumber . ').',
                direction: WalletLedger::DIRECTION_DEBIT,
                paymentId: null,
                withdrawalId: null,
                source: self::SOURCE_ADMIN_WITHDRAWAL,
                createdBy: $createdBy,
            );

            return [
                'success' => (bool) $ledger,
                'message' => $ledger
                    ? 'Penarikan Rp ' . number_format($amount, 0, ',', '.') . ' berhasil dicatat.'
                    : 'Gagal mencatat penarikan.',
                'ledger'  => $ledger,
            ];
        });
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

    /** Pendapatan platform bulan berjalan. */
    public static function monthlyIncome(): float
    {
        return (float) WalletLedger::whereNull('user_id')
            ->where('direction', WalletLedger::DIRECTION_CREDIT)
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<', now()->startOfMonth()->addMonth())
            ->sum('amount');
    }

    /** Pengeluaran platform bulan berjalan. */
    public static function monthlyExpense(): float
    {
        return (float) WalletLedger::whereNull('user_id')
            ->where('direction', WalletLedger::DIRECTION_DEBIT)
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<', now()->startOfMonth()->addMonth())
            ->sum('amount');
    }

    // ──────────────────────────────────────────────────────────────────
    // INTERNAL
    // ──────────────────────────────────────────────────────────────────

    protected static function record(
        string $type,
        float $amount,
        string $description,
        string $direction,
        ?int $paymentId,
        ?int $withdrawalId,
        string $source,
        ?int $createdBy,
    ): ?WalletLedger {
        if ($amount <= 0) {
            return null;
        }

        return DB::transaction(function () use ($type, $amount, $description, $direction, $paymentId, $withdrawalId, $source, $createdBy) {
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