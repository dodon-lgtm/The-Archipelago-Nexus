<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Report V3 - Migrasi status laporan menjadi 5 status.
 *
 * Status lama (V1/V2):
 *   menunggu, diproses, selesai, ditolak
 *
* Status baru (V3):
 *   - menunggu        : Menunggu
 *   - ditinjau        : Sedang Ditinjau (menggantikan 'diproses')
 *   - menunggu-bukti  : Menunggu Bukti Tambahan
 *   - selesai         : Selesai
 *   - ditolak         : Ditolak
 *
 * Proses:
 *   1. Update data lama: status 'diproses' -> 'ditinjau'.
 *   2. Alter enum kolom status di MySQL (raw SQL, karena Laravel
 *      tidak mendukung alter enum secara native untuk MySQL).
 *
 * Backward-compatible: nilai 'diproses' dipertahankan di level PHP
 * (lihat Report::STATUS_DIPROSES) agar data V2 tetap terbaca.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Migrasi data lama 'diproses' -> 'ditinjau'
        DB::table('reports')
            ->where('status', 'diproses')
            ->update(['status' => 'ditinjau']);

                // 2. Alter enum kolom status (MySQL). MySQL-only; pada SQLite (test in-memory)
        //    dilewati agar kolom 'status' hasil create migration tetap dipakai sebagai string.
        $enum = ['menunggu', 'ditinjau', 'menunggu-bukti', 'selesai', 'ditolak'];

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE reports MODIFY COLUMN status ENUM('" . implode("','", $enum) . "') NOT NULL DEFAULT 'menunggu'"
            );
        }
    }

    public function down(): void
    {
        // Rollback: kembalikan 'ditinjau' & 'menunggu-bukti' ke 'diproses'
        DB::table('reports')
            ->whereIn('status', ['ditinjau', 'menunggu-bukti'])
            ->update(['status' => 'diproses']);

                // Kembalikan enum lama (MySQL-only)
        $enum = ['menunggu', 'diproses', 'selesai', 'ditolak'];

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE reports MODIFY COLUMN status ENUM('" . implode("','", $enum) . "') NOT NULL DEFAULT 'menunggu'"
            );
        }
    }
};
