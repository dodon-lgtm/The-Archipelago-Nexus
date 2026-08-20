<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NegotiationMessage extends Model
{
    public const STATUS_PENDING  = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'penawaran_id',
        'sender_id',
        'sender_type',
        'message',
        'proposed_price',
        'proposed_days',
        'status',
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
        'proposed_days'  => 'integer',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    public function penawaran(): BelongsTo
    {
        return $this->belongsTo(Penawaran::class, 'penawaran_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function isOffer(): bool
    {
        return $this->proposed_price !== null || $this->proposed_days !== null;
    }
}