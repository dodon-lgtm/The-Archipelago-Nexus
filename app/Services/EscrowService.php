<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Report;
use App\Models\WalletLedger;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * EscrowService — Pusat mutasi dana tertahan (held/escrow), release, refund, split.
 *
 * Prinsip:
 * - SEMUA pergerakan dana wajib melalui service ini.
 * - SEMUA operasi dibungkus DB::transaction() + lockForUpdate pada baris Payment
 *   agar terhindar dari double release / double refund / race condition
 *   (webhook vs admin vs company acceptance berjalan bersamaan).
 * - SETIAP mutasi dicatat ke tabel wallet_ledger (audit trail).
 *
 * Lifecycle dana:
 *   not_applicable
 *       → hold()            (saat payment menjadi paid, manual atau Midtrans)
 *       → held
 *       → dispute()         (opsional, saat Report dibuat terkait dana held)
 *       → disputed
 *       → release()/refund()/partialRelease()/partialRefund()  (keputusan Company/Admin)
 *       → released / refunded / released_partial / refunded_partial
 */
class EscrowService
{
    /**
     * Tahan dana setelah pembayaran sukses (payments.status = paid).
     * Idempotent: bila dana sudah tidak 'not_applicable', tidak melakukan apa-apa.
     */
    public function hold(Payment $payment, ?string $description = null, ?int $createdBy = null): bool
    {
        return DB::transaction(function () use ($payment, $description, $createdBy) {
            $payment = $this->lockPayment($payment);

            if ($payment->funds_status !== Payment::FUNDS_NOT_APPLICABLE) {
                return false; // sudah held / disputed / resolved -> no-op aman
            }

            if ($payment->status !== 'paid') {
                throw new RuntimeException('Payment belum berstatus paid, dana tidak dapat ditahan.');
            }

            $payment->update([
                'funds_status' => Payment::FUNDS_HELD,
                'held_at' => now(),
            ]);

            $this->record(
                userId: $payment->company_id,
                workspaceId: $payment->workspace_id,
                paymentId: $payment->id,
                reportId: null,
                type: WalletLedger::TYPE_ESCROW_HELD,
                amount: (float) $payment->amount,
                direction: WalletLedger::DIRECTION_DEBIT,
                description: $description
                    ?? 'Dana proyek ditahan (escrow) setelah pembayaran dikonfirmasi. Company membayar Rp '
                        . number_format((float) $payment->amount, 0, ',', '.') . '.',
                createdBy: $createdBy,
            );

            return true;
        });
    }

    /**
     * Release penuh (dana → freelancer, fee → platform).
     * Bila $releaseAmount diberikan dan kurang dari freelancer_receive, sisanya
     * dikembalikan ke company (partial/split). Idempotent terhadap state resolved.
     */
    public function release(
        Payment $payment,
        ?Report $report = null,
        ?string $description = null,
        ?int $createdBy = null,
        ?float $releaseAmount = null,
    ): bool {
        $releaseAmount ??= (float) $payment->freelancer_receive;

        return $this->resolve(
            payment: $payment,
            report: $report,
            releaseAmount: $releaseAmount,
            description: $description ?? 'Dana proyek dirilis ke freelancer karena proyek selesai/disetujui.',
            createdBy: $createdBy,
        );
    }

    /**
     * Refund penuh (dana dikembalikan ke company, fee platform tidak diambil).
     * Bila $refundAmount diberikan dan kurang dari freelancer_receive, sisanya
     * dirilis ke freelancer (partial/split). Idempotent terhadap state resolved.
     */
    public function refund(
        Payment $payment,
        ?Report $report = null,
        ?string $description = null,
        ?int $createdBy = null,
        ?float $refundAmount = null,
    ): bool {
        $receive = (float) $payment->freelancer_receive;
        $refund = $refundAmount ?? PHP_FLOAT_MAX; // default: refund penuh
        $releaseAmount = max(0.0, $receive - $refund);

        return $this->resolve(
            payment: $payment,
            report: $report,
            releaseAmount: $releaseAmount,
            description: $description ?? 'Dana dikembalikan ke company sesuai keputusan Admin.',
            createdBy: $createdBy,
        );
    }

    /**
     * Split eksplisit: freelancer menerima $freelancerAmount, company menerima
     * $refundAmount. Jumlah keduanya WAJIB sama dengan freelancer_receive
     * (tidak boleh ada nominal yang hilang; fee platform di luar pembagian).
     */
    public function partialRelease(
        Payment $payment,
        float $freelancerAmount,
        float $refundAmount,
        ?Report $report = null,
        ?string $description = null,
        ?int $createdBy = null,
    ): bool {
        $this->assertSplitValid($freelancerAmount, $refundAmount, (float) $payment->freelancer_receive);

        return $this->resolve(
            payment: $payment,
            report: $report,
            releaseAmount: $freelancerAmount,
            description: $description
                ?? 'Pembagian dana (split) oleh Admin: freelancer Rp '
                    . number_format($freelancerAmount, 0, ',', '.') . ', company Rp '
                    . number_format($refundAmount, 0, ',', '.') . '.',
            createdBy: $createdBy,
        );
    }

    /**
     * Split eksplisit dengan sudut pandang refund sebagai nominal utama.
     */
    public function partialRefund(
        Payment $payment,
        float $refundAmount,
        float $freelancerAmount,
        ?Report $report = null,
        ?string $description = null,
        ?int $createdBy = null,
    ): bool {
        return $this->partialRelease(
            payment: $payment,
            freelancerAmount: $freelancerAmount,
            refundAmount: $refundAmount,
            report: $report,
            description: $description,
            createdBy: $createdBy,
        );
    }

    /**
     * Tandai dana yang sedang held sebagai disputed (saat Report dibuat).
     * BUKAN pemindahan dana — dana tetap tertahan sampai Admin memutuskan.
     */
    public function dispute(Payment $payment, string $reference, ?int $createdBy = null): bool
    {
        return DB::transaction(function () use ($payment, $reference, $createdBy) {
            $payment = $this->lockPayment($payment);

            if ($payment->funds_status === Payment::FUNDS_DISPUTED) {
                return false;
            }

            if ($payment->funds_status !== Payment::FUNDS_HELD) {
                return false; // hanya dana held yang bisa ditandai dispute
            }

            $payment->update([
                'funds_status' => Payment::FUNDS_DISPUTED,
                'dispute_reference' => $reference,
            ]);

            return true;
        });
    }

    /**
     * Inti resolusi (release/refund/split) dalam satu transaction.
     */
    private function resolve(
        Payment $payment,
        ?Report $report,
        float $releaseAmount,
        string $description,
        ?int $createdBy,
    ): bool {
        return DB::transaction(function () use ($payment, $report, $releaseAmount, $description, $createdBy) {
            $payment = $this->lockPayment($payment);

            // Idempotensi: dana yang sudah resolved tidak boleh di-resolve ulang.
            if (in_array($payment->funds_status, [
                Payment::FUNDS_RELEASED,
                Payment::FUNDS_RELEASED_PARTIAL,
                Payment::FUNDS_REFUNDED,
                Payment::FUNDS_REFUNDED_PARTIAL,
            ], true)) {
                return false;
            }

            $this->assertResolvable($payment);

            $split = $this->computeSplit($payment, $releaseAmount);

            $payment->update([
                'funds_status' => $split['status'],
                'released_at' => $split['released'] > 0 ? now() : null,
                'released_amount' => $split['released'],
                'refunded_at' => $split['refunded'] > 0 ? now() : null,
                'refunded_amount' => $split['refunded'],
                'dispute_reference' => $report ? ('Report #' . $report->id) : $payment->dispute_reference,
            ]);

            $reportId = $report?->id;

            if ($split['released'] > 0) {
                $this->record(
                    userId: $payment->freelancer_id,
                    workspaceId: $payment->workspace_id,
                    paymentId: $payment->id,
                    reportId: $reportId,
                    type: WalletLedger::TYPE_ESCROW_RELEASED,
                    amount: $split['released'],
                    direction: WalletLedger::DIRECTION_CREDIT,
                    description: $description . ' Freelancer menerima Rp '
                        . number_format($split['released'], 0, ',', '.') . '.',
                    createdBy: $createdBy,
                );
            }

            if ($split['fee'] > 0) {
                $this->record(
                    userId: null, // pihak platform
                    workspaceId: $payment->workspace_id,
                    paymentId: $payment->id,
                    reportId: $reportId,
                    type: WalletLedger::TYPE_FEE_EARNED,
                    amount: $split['fee'],
                    direction: WalletLedger::DIRECTION_CREDIT,
                    description: 'Biaya platform (platform fee) dicatat sebagai pendapatan platform sebesar Rp '
                        . number_format($split['fee'], 0, ',', '.') . '.',
                    createdBy: $createdBy,
                );
            }

            if ($split['refunded'] > 0) {
                $this->record(
                    userId: $payment->company_id,
                    workspaceId: $payment->workspace_id,
                    paymentId: $payment->id,
                    reportId: $reportId,
                    type: WalletLedger::TYPE_REFUND_ISSUED,
                    amount: $split['refunded'],
                    direction: WalletLedger::DIRECTION_CREDIT,
                    description: $description . ' Company menerima refund Rp '
                        . number_format($split['refunded'], 0, ',', '.') . '.',
                    createdBy: $createdBy,
                );
            }

            return true;
        });
    }


    /**
     * Hitung pembagian nominal berdasarkan jumlah release yang diminta.
     * Konsisten: released + refunded + fee selalu = amount (tidak ada yang hilang).
     */
    private function computeSplit(Payment $payment, float $releaseAmount): array
    {
        $receive = (float) $payment->freelancer_receive;
        $fee = (float) $payment->platform_fee;
        $amount = (float) $payment->amount;

        if ($releaseAmount < 0 || $releaseAmount > $receive + 0.01) {
            throw new RuntimeException('Nominal release tidak valid (di luar dana tertahan).');
        }

        if ($releaseAmount >= $receive - 0.001) {
            // Release penuh: freelancer menerima freelancer_receive, platform menerima fee.
            return [
                'status' => Payment::FUNDS_RELEASED,
                'released' => $receive,
                'refunded' => 0.0,
                'fee' => $fee,
            ];
        }

        if ($releaseAmount <= 0.001) {
            // Refund penuh: company menerima seluruh amount, fee platform tidak diambil.
            return [
                'status' => Payment::FUNDS_REFUNDED,
                'released' => 0.0,
                'refunded' => $amount,
                'fee' => 0.0,
            ];
        }

        // Partial/split: freelancer mendapat $releaseAmount, company mendapat sisanya.
        return [
            'status' => Payment::FUNDS_RELEASED_PARTIAL,
            'released' => $releaseAmount,
            'refunded' => round($receive - $releaseAmount, 2),
            'fee' => $fee,
        ];
    }

    private function assertResolvable(Payment $payment): void
    {
        if ($payment->status !== 'paid') {
            throw new RuntimeException('Payment belum berstatus paid.');
        }

        if (!in_array($payment->funds_status, [Payment::FUNDS_HELD, Payment::FUNDS_DISPUTED], true)) {
            throw new RuntimeException('Dana tidak dalam status tertahan (held/disputed), tidak dapat di-resolve.');
        }
    }

    private function assertSplitValid(float $freelancerAmount, float $refundAmount, float $receive): void
    {
        if ($freelancerAmount < 0 || $refundAmount < 0) {
            throw new RuntimeException('Nominal pembagian tidak boleh negatif.');
        }

        if (abs(($freelancerAmount + $refundAmount) - $receive) > 0.01) {
            throw new RuntimeException(
                'Total pembagian (' . number_format($freelancerAmount + $refundAmount, 0, ',', '.')
                . ') harus sama dengan dana yang tersedia ('
                . number_format($receive, 0, ',', '.') . ').'
            );
        }
    }

    private function lockPayment(Payment $payment): Payment
    {
        return Payment::whereKey($payment->getKey())->lockForUpdate()->firstOrFail();
    }

    private function record(
        ?int $userId,
        int $workspaceId,
        int $paymentId,
        ?int $reportId,
        string $type,
        float $amount,
        string $direction,
        string $description,
        ?int $createdBy,
    ): void {
        if ($amount <= 0) {
            return;
        }

        WalletLedger::create([
            'user_id' => $userId,
            'workspace_id' => $workspaceId,
            'payment_id' => $paymentId,
            'report_id' => $reportId,
            'type' => $type,
            'amount' => $amount,
            'direction' => $direction,
            'balance_after' => $this->balanceAfter($userId, $direction === WalletLedger::DIRECTION_CREDIT ? $amount : -$amount),
            'description' => $description,
            'created_by' => $createdBy,
        ]);
    }

    private function balanceAfter(?int $userId, float $delta): float
    {
        $query = WalletLedger::query();
        if ($userId === null) {
            $query->whereNull('user_id');
        } else {
            $query->where('user_id', $userId);
        }

        $current = (float) $query
            ->selectRaw('COALESCE(SUM(CASE WHEN direction = "credit" THEN amount ELSE -amount END), 0) AS total')
            ->value('total');

        return round($current + $delta, 2);
    }
}

