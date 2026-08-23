<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    use HasFactory;

    protected $table = 'footer_settings';

    protected $fillable = [
        'privacy_policy_content',
        'terms_conditions_content',
        'support_email',
        'about_text',
        'copyright_text',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Ambil record pengaturan footer (single-row).
     * Mengembalikan instance kosong (tanpa menyimpan) bila belum ada data.
     */
    public static function getSettings(): self
    {
        return static::query()->firstOrNew([]);
    }
}