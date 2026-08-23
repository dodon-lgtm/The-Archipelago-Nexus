<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * WalletLedger — Audit trail seluruh pergerakan dana (escrow/release/refund/fee).
 *
 * Setiap mutasi uang harus dicatat melalui App\Services\EscrowService agar
 * transaksi, idempotensi, dan konsistensi nominal terjaga.
 */
class WalletLedger extends Model
{
    protected $table = 'wallet_ledger';

    public const TYPE_ESCROW_HELD   = 'escrow_held';
    public const TYPE_ESCROW_RELEASED = 'escrow_released';
    public const TYPE_REFUND_ISSUED = 'refund_issued';
    public const TYPE_FEE_EARNED    = 'fee_earned';
    public const TYPE_ADMIN_ADJUSTMENT = 'admin_adjustment';

    // ─── TYPE BARU — Admin Wallet (Platform) ─────────────────────────
    public const TYPE_PROJECT_QUOTA_FEE = 'project_quota_fee';
    public const TYPE_WITHDRAWAL_FEE    = 'withdrawal_fee';
    public const TYPE_ADMIN_EXPENSE     = 'admin_expense';
    public const TYPE_ADMIN_WITHDRAWAL  = 'admin_withdrawal';

    public const DIRECTION_CREDIT = 'credit';
    public const DIRECTION_DEBIT  = 'debit';

    protected $fillable = [
        'user_id',
        'workspace_id',
        'payment_id',
        'withdrawal_id',
        'report_id',
        'type',
        'source',
        'amount',
        'direction',
        'balance_after',
        'description',
        'meta',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'meta' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function withdrawal(): BelongsTo
    {
        return $this->belongsTo(Withdrawal::class, 'withdrawal_id');
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── LABEL & KODE DISPLAY (untuk Admin Wallet dashboard) ─────────

    /**
     * Label bahasa Indonesia untuk jenis transaksi (kolom `type`).
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_ESCROW_HELD       => 'Escrow Ditahan',
            self::TYPE_ESCROW_RELEASED   => 'Dana Dirilis',
            self::TYPE_REFUND_ISSUED     => 'Refund',
            self::TYPE_FEE_EARNED        => 'Platform Fee',
            self::TYPE_ADMIN_ADJUSTMENT  => 'Penyesuaian Admin',
            self::TYPE_PROJECT_QUOTA_FEE => 'Biaya Upload Project',
            self::TYPE_WITHDRAWAL_FEE    => 'Fee Withdrawal',
            self::TYPE_ADMIN_EXPENSE     => 'Pengeluaran Admin',
            self::TYPE_ADMIN_WITHDRAWAL  => 'Tarik Saldo Admin',
            default                      => (string) $this->type,
        };
    }

    /**
     * Label arah transaksi.
     */
    public function getDirectionLabelAttribute(): string
    {
        return $this->direction === self::DIRECTION_CREDIT ? 'Pendapatan' : 'Pengeluaran';
    }

    /**
     * Kode referensi yang dapat ditampilkan pada riwayat wallet.
     *
     * - Pendapatan kuota / platform fee → invoice_number payment terkait.
     * - Fee withdrawal                  → withdrawal_code penarikan terkait.
     * - Pengeluaran admin               → EXP-00001 (diturunkan dari id ledger,
     *                                     deterministik, tanpa kolom tambahan).
     * - Penarikan admin                 → WD-ADMIN-00001 (idem).
     */
    public function getDisplayCodeAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_PROJECT_QUOTA_FEE,
            self::TYPE_FEE_EARNED => $this->payment?->invoice_number
                ?? ('PAY-' . str_pad((string) $this->payment_id, 5, '0', STR_PAD_LEFT)),

            self::TYPE_WITHDRAWAL_FEE => $this->withdrawal?->withdrawal_code
                ?? ('WD-' . str_pad((string) $this->withdrawal_id, 5, '0', STR_PAD_LEFT)),

            self::TYPE_ADMIN_EXPENSE    => 'EXP-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT),
            self::TYPE_ADMIN_WITHDRAWAL => 'WD-ADMIN-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT),

            default => 'WL-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT),
        };
    }

    /**
     * Saldo platform SEBELUM mutasi ini (untuk tampilan riwayat).
     * Hanya bermakna untuk baris platform (user_id NULL).
     */
    public function getBalanceBeforeAttribute(): float
    {
        if ($this->balance_after === null) {
            return 0.0;
        }

        $delta = $this->direction === self::DIRECTION_CREDIT
            ? (float) $this->amount
            : -(float) $this->amount;

        return round((float) $this->balance_after - $delta, 2);
    }
}
