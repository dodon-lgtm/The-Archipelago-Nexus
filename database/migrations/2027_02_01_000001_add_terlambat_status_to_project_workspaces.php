<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menambah status 'Terlambat' pada ENUM project_workspaces.status.
     *
     * Sifat migration: ADDITIF.
     * Tidak menghapus / merename status lama.
     * Tidak menghapus / mengubah data workspace yang ada.
     * Hanya menambahkan pilihan baru pada ENUM.
     *
     * Makna:
     * 'Terlambat' : Deadline proyek telah lewat dan pekerjaan belum selesai
     *               (freelancer masih berstatus 'Sedang Dikerjakan' / 'Menunggu Revisi').
     */
    public function up(): void
    {
        // RAW "ALTER TABLE ... MODIFY COLUMN" (MySQL-only). Dipakai hanya pada
        // MySQL agar ENUM tetap konsisten. Pada SQLite (test in-memory) dilewati;
        // kolom 'status' hasil migration create tetap dipakai sebagai string
        // dan sudah mencantumkan 'Terlambat' sebagai superset.
        if (DB::getDriverName() === 'mysql') {
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

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE project_workspaces MODIFY COLUMN status ENUM(
                'Sedang Dikerjakan',
                'Menunggu Review',
                'Menunggu Revisi',
                'Menunggu Pembayaran',
                'Menunggu Verifikasi Admin',
                'Selesai'
            ) DEFAULT 'Sedang Dikerjakan'");
        }
    }
};