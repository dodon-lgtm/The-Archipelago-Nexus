<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'workspace_id',
        'company_id',
        'freelancer_id',
        'invoice_number',
        'amount',
        'platform_fee',
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
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'freelancer_receive' => 'decimal:2',
        'midtrans_response' => 'array',
        'verified_at' => 'datetime',
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

