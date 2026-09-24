<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserConsent extends Model
{
    use HasFactory;

    protected $table = 'user_consents';

    protected $fillable = [
        'user_id',
        'policy_id',
        'policy_version',
        'is_required',
        'accepted_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_required'  => 'boolean',
        'accepted_at'  => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
