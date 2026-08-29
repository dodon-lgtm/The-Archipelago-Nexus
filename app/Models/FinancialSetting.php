<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * FinancialSetting — pengaturan keuangan platform (single-row, dikelola Admin).
 *
 * Hanya ada SATU konfigurasi aktif. Dipakai sebagai source-of-truth untuk
 * AKSI BARU (pembuatan Payment proyek, withdrawal, dan kuota upload).
 * Transaksi lama sudah menyimpan snapshot masing-masing dan TIDAK dihitung ulang.
 */
class FinancialSetting extends Model
{
    protected $table = 'financial_settings';

    /** Nilai bawaan, dipakai bila belum ada baris di tabel. */
    public const DEFAULT_PROJECT_FEE_RATE = 5.00;
    public const DEFAULT_WITHDRAWAL_FEE_RATE = 5.00;
    public const DEFAULT_FREE_UPLOADS = 3;
    public const DEFAULT_UPLOAD_PRICE = 10000.00;

    protected $fillable = [
        'project_fee_rate',
        'withdrawal_fee_rate',
        'free_project_uploads_per_month',
        'paid_project_upload_price',
    ];

    protected $casts = [
        'project_fee_rate' => 'decimal:2',
        'withdrawal_fee_rate' => 'decimal:2',
        'free_project_uploads_per_month' => 'integer',
        'paid_project_upload_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Ambil setting aktif (single-row). Aman bila belum ada baris:
     * mengembalikan instance berisi nilai DEFAULT tanpa menyimpannya ke DB.
     */
    public static function getSettings(): self
    {
        $row = static::query()->first();

        if ($row) {
            return $row;
        }

        $instance = new static();
        $instance->project_fee_rate = self::DEFAULT_PROJECT_FEE_RATE;
        $instance->withdrawal_fee_rate = self::DEFAULT_WITHDRAWAL_FEE_RATE;
        $instance->free_project_uploads_per_month = self::DEFAULT_FREE_UPLOADS;
        $instance->paid_project_upload_price = self::DEFAULT_UPLOAD_PRICE;

        return $instance;
    }

    /** Fee platform proyek (%) saat ini. */
    public function projectFeeRate(): float
    {
        return (float) $this->project_fee_rate;
    }

    /** Fee withdrawal freelancer (%) saat ini. */
    public function withdrawalFeeRate(): float
    {
        return (float) $this->withdrawal_fee_rate;
    }

    /** Jumlah upload gratis per company per bulan. */
    public function freeUploadsPerMonth(): int
    {
        return (int) $this->free_project_uploads_per_month;
    }

    /** Harga upload setelah kuota gratis habis. */
    public function paidUploadPrice(): float
    {
        return (float) $this->paid_project_upload_price;
    }
}
