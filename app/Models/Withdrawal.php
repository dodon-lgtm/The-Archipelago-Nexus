<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Withdrawal extends Model
{
    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_BERHASIL = 'berhasil';
    public const STATUS_DITOLAK = 'ditolak';

    public const METHOD_BANK = 'bank';
    public const METHOD_EWALLET = 'ewallet';

    /** Jenis penarikan: freelancer (flow existing) atau admin (saldo platform). */
    public const TYPE_FREELANCER = 'freelancer';
    public const TYPE_ADMIN = 'admin';

    /** Pajak admin yang dipotong dari setiap penarikan (5%). */
    public const TAX_RATE = 0.05;

    public const ACTIVE_STATUSES = [
        self::STATUS_MENUNGGU,
        self::STATUS_DIPROSES,
    ];

    protected $fillable = [
        'withdrawal_code',
        'withdrawal_type',
        'user_id',
        'amount',
        'fee',
        'net_amount',
        'method',
        'bank_name',
        'account_name',
        'account_number',
        'status',
        'rejection_reason',
        'processed_by',
        'processed_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', self::ACTIVE_STATUSES);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('withdrawal_type', $type);
    }

    public function isFreelancer(): bool
    {
        return $this->withdrawal_type === self::TYPE_FREELANCER;
    }

    /**
     * Nomor rekening termasking untuk tampilan history (******7890).
     */
    public function getMaskedAccountNumberAttribute(): string
    {
        $number = (string) $this->account_number;

        if ($number === '') {
            return '-';
        }

        $tail = substr($number, -4);

        return str_repeat('*', max(0, strlen($number) - 4)) . $tail;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_MENUNGGU => 'Menunggu',
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_BERHASIL => 'Berhasil',
            self::STATUS_DITOLAK => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_MENUNGGU => 'bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-300 border-amber-200 dark:border-amber-900',
            self::STATUS_DIPROSES => 'bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 border-blue-200 dark:border-blue-900',
            self::STATUS_BERHASIL => 'bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900',
            self::STATUS_DITOLAK => 'bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-300 border-red-200 dark:border-red-900',
            default => 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700',
        };
    }

    public function getMethodLabelAttribute(): string
    {
        return $this->method === self::METHOD_BANK ? 'Bank' : 'E-Wallet';
    }
}