<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menambah status 'Menunggu Review' pada ENUM project_workspaces.status.
     *
     * Sifat migration: ADDITIF.
     * Tidak menghapus / merename status lama.
     * Tidak menghapus / mengubah data workspace yang ada.
     * Hanya menambahkan pilihan baru pada ENUM.
     *
     * Makna:
     * 'Menunggu Review' : Freelancer telah mencapai 100%, menunggu Company memeriksa.
     * 'Menunggu Revisi' : (tetap) Company eksplisit meminta revisi setelah review.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE project_workspaces MODIFY COLUMN status ENUM(
            'Sedang Dikerjakan',
            'Menunggu Review',
            'Menunggu Revisi',
            'Menunggu Pembayaran',
            'Menunggu Verifikasi Admin',
            'Selesai'
        ) DEFAULT 'Sedang Dikerjakan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE project_workspaces MODIFY COLUMN status ENUM(
            'Sedang Dikerjakan',
            'Menunggu Revisi',
            'Menunggu Pembayaran',
            'Menunggu Verifikasi Admin',
            'Selesai'
        ) DEFAULT 'Sedang Dikerjakan'");
    }
};

