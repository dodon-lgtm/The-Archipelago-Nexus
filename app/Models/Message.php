<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'workspace_id',
        'sender_id',
        'message',
        'type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scope: pesan masuk (chat antar user, BUKAN pesan sistem) yang belum
     * dibaca oleh user tertentu, pada workspace yang diikutinya
     * (sebagai freelancer maupun company).
     *
     * Dipakai untuk indikator jumlah "Pesan Baru" pada dashboard.
     */
    public function scopeUnreadIncomingFor(Builder $query, int $userId): Builder
    {
        return $query
            ->where('is_read', false)
            ->where('type', 'user')
            ->where('sender_id', '!=', $userId)
            ->whereIn('workspace_id', Workspace::query()
                ->select('id')
                ->where('freelancer_id', $userId)
                ->orWhere('company_id', $userId));
    }

    /**
     * Tandai semua pesan dalam sebuah room chat yang BUKAN dikirim oleh
     * $userId sebagai terbaca. Dipanggil saat user membuka room chat pada
     * halaman Workspace, sehingga indikator "Pesan Baru" di dashboard
     * menjadi akurat.
     *
     * Return: jumlah baris yang berhasil ditandai terbaca.
     */
    public static function markAsReadForUser(int $workspaceId, int $userId): int
    {
        return static::query()
            ->where('workspace_id', $workspaceId)
            ->where('is_read', false)
            ->where(function (Builder $query) use ($userId) {
                $query->where('sender_id', '!=', $userId)
                    ->orWhereNull('sender_id');
            })
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}

