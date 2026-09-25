<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Policy extends Model
{
    use HasFactory;

    protected $table = 'policies';

    protected $fillable = [
        'key',
        'title',
        'content',
        'version',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    /** Kunci standar dokumen. */
    public const KEY_PRIVACY = 'privacy';
    public const KEY_USAGE   = 'usage';
    public const KEY_TERMS   = 'terms';

    /** Scope: hanya dokumen yang sedang ditampilkan. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequired($query)
    {
        return $query->whereIn('key', [self::KEY_PRIVACY, self::KEY_TERMS]);
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    public function isRequired(): bool
    {
        return in_array($this->key, [self::KEY_PRIVACY, self::KEY_TERMS]);
    }

    /** Ringkasan isi untuk preview daftar (tanpa tag / multi-spasi). */
    public function excerpt(int $length = 160): string
    {
        $text = trim(preg_replace('/\s+/', ' ', (string) strip_tags($this->content)));

        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length) . '...';
    }

    /** Relasi ke consent history. */
    public function consents(): HasMany
    {
        return $this->hasMany(UserConsent::class);
    }

    /** Cek apakah user sudah menyetujui versi terbaru. */
    public function isAcceptedByUser(int $userId): bool
    {
        return $this->consents()
            ->where('user_id', $userId)
            ->where('policy_version', $this->version)
            ->exists();
    }
}
