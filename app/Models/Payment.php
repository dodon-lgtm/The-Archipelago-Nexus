<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $table = 'payments';

    // ─── PAYMENT TYPE ────────────────────────────────────────────────
    // Jenis payment:
    //   workspace      : pembayaran proyek ber-workspace (flow existing).
    //   project_quota  : pembayaran kuota proyek tambahan Rp10.000 (tanpa workspace).
    public const PAYMENT_TYPE_WORKSPACE = 'workspace';
    public const PAYMENT_TYPE_QUOTA    = 'quota';

    public const PAYMENT_TYPES = [
        self::PAYMENT_TYPE_WORKSPACE,
        self::PAYMENT_TYPE_QUOTA,
    ];

    // ─── FUNDS STATUS (state dana tertahan / escrow) ─────────────────
    // Terpisah dari payments.status agar tidak merusak alur pembayaran lama.
    public const FUNDS_NOT_APPLICABLE   = 'not_applicable';
    public const FUNDS_HELD             = 'held';
    public const FUNDS_DISPUTED         = 'disputed';
    public const FUNDS_RELEASED         = 'released';
    public const FUNDS_REFUNDED         = 'refunded';
    public const FUNDS_RELEASED_PARTIAL = 'released_partial';
    public const FUNDS_REFUNDED_PARTIAL = 'refunded_partial';

    public const FUNDS_STATUSES = [
        self::FUNDS_NOT_APPLICABLE,
        self::FUNDS_HELD,
        self::FUNDS_DISPUTED,
        self::FUNDS_RELEASED,
        self::FUNDS_REFUNDED,
        self::FUNDS_RELEASED_PARTIAL,
        self::FUNDS_REFUNDED_PARTIAL,
    ];

    protected $fillable = [
        'workspace_id',
        'payment_type',
        'company_id',
        'freelancer_id',
        'invoice_number',
        'amount',
        'platform_fee',
        'platform_fee_rate',
        'freelancer_receive',
        'payment_method',
        'payment_proof',
        'company_note',
        'admin_note',
        'status',
        'verified_by',
        'verified_at',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'midtrans_response',
        'funds_status',
        'held_at',
        'released_at',
        'refunded_at',
        'released_amount',
        'refunded_amount',
        'dispute_reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'platform_fee_rate' => 'decimal:2',
        'freelancer_receive' => 'decimal:2',
        'midtrans_response' => 'array',
        'verified_at' => 'datetime',
        'held_at' => 'datetime',
        'released_at' => 'datetime',
        'refunded_at' => 'datetime',
        'released_amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'funds_status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function walletLedgers(): HasMany
    {
        return $this->hasMany(WalletLedger::class, 'payment_id');
    }

    public function midtransAttempts(): HasMany
    {
        return $this->hasMany(MidtransAttempt::class, 'payment_id');
    }

    /**
     * Apakah dana sedang tertahan (held atau disputed)?
     */
    public function isFundsHeld(): bool
    {
        return in_array($this->funds_status, [
            self::FUNDS_HELD,
            self::FUNDS_DISPUTED,
        ], true);
    }

    /**
     * Apakah dana sudah terselesaikan (released / refunded / partial)?
     */
    public function isFundsResolved(): bool
    {
        return in_array($this->funds_status, [
            self::FUNDS_RELEASED,
            self::FUNDS_RELEASED_PARTIAL,
            self::FUNDS_REFUNDED,
            self::FUNDS_REFUNDED_PARTIAL,
        ], true);
    }

    /**
     * Apakah payment ini adalah pembayaran kuota proyek tambahan?
     */
    public function isQuotaPayment(): bool
    {
        return $this->payment_type === self::PAYMENT_TYPE_QUOTA;
    }

    /**
     * Label bahasa Indonesia untuk funds_status.
     */
    public function getFundsStatusLabelAttribute(): string
    {
        return match ($this->funds_status) {
            self::FUNDS_HELD => 'Dana Ditahan',
            self::FUNDS_DISPUTED => 'Dana Dispute',
            self::FUNDS_RELEASED => 'Dana Dirilis',
            self::FUNDS_REFUNDED => 'Dana Direfund',
            self::FUNDS_RELEASED_PARTIAL => 'Dirilis Sebagian',
            self::FUNDS_REFUNDED_PARTIAL => 'Direfund Sebagian',
            default => 'Belum Ada Dana',
        };
    }

    /**
     * Warna badge untuk funds_status.
     */
    public function getFundsStatusColorAttribute(): string
    {
        return match ($this->funds_status) {
            self::FUNDS_HELD => 'bg-amber-50 text-amber-600 border-amber-200',
            self::FUNDS_DISPUTED => 'bg-red-50 text-red-600 border-red-200',
            self::FUNDS_RELEASED, self::FUNDS_RELEASED_PARTIAL => 'bg-emerald-50 text-emerald-600 border-emerald-200',
            self::FUNDS_REFUNDED, self::FUNDS_REFUNDED_PARTIAL => 'bg-slate-100 text-slate-600 border-slate-200',
            default => 'bg-slate-50 text-slate-400 border-slate-200',
        };
    }

    /**
     * Get status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'waiting_verification' => 'Menunggu Verifikasi',
            'paid' => 'Dibayar',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get status badge color class.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-slate-50 text-slate-600 border-slate-200',
            'waiting_verification' => 'bg-amber-50 text-amber-600 border-amber-200',
            'paid' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
            'rejected' => 'bg-red-50 text-red-600 border-red-200',
            default => 'bg-slate-50 text-slate-600 border-slate-200',
        };
    }
}

