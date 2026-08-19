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

    public const DIRECTION_CREDIT = 'credit';
    public const DIRECTION_DEBIT  = 'debit';

    protected $fillable = [
        'user_id',
        'workspace_id',
        'payment_id',
        'report_id',
        'type',
        'amount',
        'direction',
        'balance_after',
        'description',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
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

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
