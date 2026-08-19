<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $table = 'policies';

    protected $fillable = [
        'key',
        'title',
        'content',
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

    /** Scope: hanya dokumen yang sedang ditampilkan. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
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
}
