<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * MidtransAttempt — Tracking percobaan pembayaran Midtrans (order_id unik per attempt).
 */
class MidtransAttempt extends Model
{
    protected $table = 'midtrans_attempts';

    protected $fillable = [
        'payment_id',
        'order_id',
        'status',
        'raw_response',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
