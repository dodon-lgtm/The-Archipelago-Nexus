<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Rename status 'Terlambat' menjadi 'Melewati Batas Waktu' pada
     * ENUM project_workspaces.status.
     *
     * Makna status TIDAK berubah:
     * 'Melewati Batas Waktu' : Deadline proyek telah lewat dan pekerjaan
     *                          belum selesai (workspace sebelumnya berstatus
     *                          'Sedang Dikerjakan' / 'Menunggu Revisi').
     *
     * Sifat migration: RENAME nilai ENUM (menggantikan 'Terlambat').
     * - Data existing berstatus 'Terlambat' ikut dikonversi ke nilai baru.
     * - Status lain tidak disentuh.
     * - Urutan ALTER MySQL aman untuk strict mode: ENUM diperluas dulu
     *   (memuat dua nilai), baru data dikonversi, lalu nilai lama dibuang.
     * - Pada driver lain (mis. SQLite untuk test in-memory) kolom 'status'
     *   memakai CHECK constraint dari migration create (superset terbaru),
     *   sehingga hanya konversi data yang dijalankan (aman/no-op).
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // 1. Tambah nilai baru dulu tanpa menghapus nilai lama (additif).
            DB::statement("ALTER TABLE project_workspaces MODIFY COLUMN status ENUM(
                'Sedang Dikerjakan',
                'Menunggu Review',
                'Menunggu Revisi',
                'Menunggu Pembayaran',
                'Menunggu Verifikasi Admin',
                'Selesai',
                'Terlambat',
                'Melewati Batas Waktu'
            ) DEFAULT 'Sedang Dikerjakan'");
        }

        // 2. Konversi data lama ke nilai baru (aman di semua driver).
        DB::table('project_workspaces')
            ->where('status', 'Terlambat')
            ->update(['status' => 'Melewati Batas Waktu']);

        if (DB::getDriverName() === 'mysql') {
            // 3. Buang nilai lama dari ENUM.
            DB::statement("ALTER TABLE project_workspaces MODIFY COLUMN status ENUM(
                'Sedang Dikerjakan',
                'Menunggu Review',
                'Menunggu Revisi',
                'Menunggu Pembayaran',
                'Menunggu Verifikasi Admin',
                'Selesai',
                'Melewati Batas Waktu'
            ) DEFAULT 'Sedang Dikerjakan'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // 1. Kembalikan nilai lama tanpa menghapus nilai baru (additif).
            DB::statement("ALTER TABLE project_workspaces MODIFY COLUMN status ENUM(
                'Sedang Dikerjakan',
                'Menunggu Review',
                'Menunggu Revisi',
                'Menunggu Pembayaran',
                'Menunggu Verifikasi Admin',
                'Selesai',
                'Terlambat',
                'Melewati Batas Waktu'
            ) DEFAULT 'Sedang Dikerjakan'");
        }

        // 2. Konversi data kembali ke nilai lama.
        DB::table('project_workspaces')
            ->where('status', 'Melewati Batas Waktu')
            ->update(['status' => 'Terlambat']);

        if (DB::getDriverName() === 'mysql') {
            // 3. Buang nilai baru dari ENUM.
            DB::statement("ALTER TABLE project_workspaces MODIFY COLUMN status ENUM(
                'Sedang Dikerjakan',
                'Menunggu Review',
                'Menunggu Revisi',
                'Menunggu Pembayaran',
                'Menunggu Verifikasi Admin',
                'Selesai',
                'Terlambat'
            ) DEFAULT 'Sedang Dikerjakan'");
        }
    }
};