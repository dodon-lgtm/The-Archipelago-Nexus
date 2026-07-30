<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'sender_id',
        'penawaran_id',
        'type',
        'workspace_id',
        'project_id',
        'payment_id',
        'company_account_request_id',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // ─── SCOPES ───────────────────────────────────────────────

    /**
     * Scope untuk mengambil notifikasi yang belum dibaca.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk mengambil notifikasi milik user tertentu.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter berdasarkan tipe notifikasi.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // ─── RELATIONS ────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function penawaran(): BelongsTo
    {
        return $this->belongsTo(Penawaran::class, 'penawaran_id');
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function companyAccountRequest(): BelongsTo
    {
        return $this->belongsTo(CompanyAccountRequest::class, 'company_account_request_id');
    }
}

