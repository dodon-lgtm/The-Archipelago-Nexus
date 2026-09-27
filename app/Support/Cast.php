<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

/**
 * Cast waktu untuk ditampilkan ke user.
 *
 * KONVENSI TIMEZONE PROJECT INI
 * -----------------------------
 *  - PENYIMPANAN : selalu UTC (config('app.timezone')). Semua timestamp
 *                   ditulis Carbon dalam timezone itu, jadi nilainya stabil
 *                   & tidak terpengaruh zona waktu server.
 *  - TAMPIL      : selalu config('app.display_timezone') → Asia/Jakarta (WIB).
 *
 * Karena itu konversi HARUS dilakukan lewat setTimezone() (perpindahan zona
 * waktu yang sesungguhnya), BUKAN adds/sub 7 jam manual:
 *  - adds(7, 'hours') benar HANYA selamanya di UTC+7 tanpa DST. Sekarang
 *    Approach manual itu rapuh & salah untuk data yang sudah tersimpan
 *    dalam bentuk lokal.
 *  - setTimezone() menafsirkan ulang instan yang sama di zona tujuan, sehingga
 *    otomatis benar untuk data lama maupun baru, dan tidak pernah menggeser
 *    nilai twice (tidak ada risiko timestamp dobel).
 */
class Cast
{
    /** Timezone tampilan aplikasi (default WIB). */
    public static function displayTimezone(): string
    {
        return config('app.display_timezone') ?: 'Asia/Jakarta';
    }

    /**
     * Ubah nilai waktu penyimpanan (UTC) ke timezone tampilan, lalu format.
     *
     * @param  \DateTimeInterface|string|null  $value
     */
    public static function wib($value, string $format = 'd M Y H:i'): string
    {
        $date = self::toWib($value);

        return $date ? $date->format($format) : '-';
    }

    /**
     * Instance Carbon SUDAH dalam timezone tampilan — untuk kasus yang butuh
     * kalkulu relatif (diffForHumans, perbandingan tanggal, dsb).
     */
    public static function toWib($value): ?CarbonInterface
    {
        $date = self::toDate($value);

        return $date?->setTimezone(self::displayTimezone());
    }

    /** Ubah input apa pun (string/DateTime/Carbon/null) jadi Carbon. */
    private static function toDate($value): ?CarbonInterface
    {
        if ($value === null || $value === '') {
            return null;
        }
        if ($value instanceof CarbonInterface) {
            return Carbon::instance($value);
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
