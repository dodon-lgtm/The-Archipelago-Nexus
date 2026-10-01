<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Menambahkan status 'ditangani' pada kolom status tabel `reports`.
 *
 * Status 'ditangani' dipakai alur "Terima Laporan Keterlambatan":
 * Admin menyatakan laporan keterlambatan VALID, laporan menjadi
 * "Ditangani" (masih terbuka untuk tindakan lanjutan seperti
 * perpanjangan deadline / pembatalan project), tanpa menyentuh dana.
 *
 * Proses (mengikuti pola migration `update_report_status_enum_mysql`):
 *   - Alter enum kolom status di MySQL (raw SQL, karena Laravel tidak
 *     mendukung alter enum secara native untuk MySQL). Driver lain dilewati.
 *
 * Catatan penamaan: tanggal file ini sengaja diletakkan SETELAH semua
 * migration yang ada agar pada fresh install enum tetap memuat 'ditangani'
 * setelah migration `update_report_status_enum_mysql` dijalankan.
 */
return new class extends Migration
{
    public function up(): void
    {
        $enum = ['menunggu', 'ditinjau', 'menunggu-bukti', 'ditangani', 'selesai', 'ditolak'];

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE reports MODIFY COLUMN status ENUM('" . implode("','", $enum) . "') NOT NULL DEFAULT 'menunggu'"
            );
        }
    }

    public function down(): void
    {
        // Kembalikan data 'ditangani' -> 'ditinjau' sebelum enum disusutkan.
        DB::table('reports')
            ->where('status', 'ditangani')
            ->update(['status' => 'ditinjau']);

        $enum = ['menunggu', 'ditinjau', 'menunggu-bukti', 'selesai', 'ditolak'];

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE reports MODIFY COLUMN status ENUM('" . implode("','", $enum) . "') NOT NULL DEFAULT 'menunggu'"
            );
        }
    }
};
