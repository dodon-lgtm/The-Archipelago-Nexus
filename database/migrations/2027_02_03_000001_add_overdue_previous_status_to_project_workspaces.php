<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambah kolom pencatat status asal workspace sebelum ditandai
     * 'Melewati Batas Waktu' oleh command workspaces:mark-overdue.
     *
     * Tujuan:
     * Saat deadline proyek dimundurkan (belum lewat lagi), command dapat
     * mengembalikan status workspace ke status asalnya masing-masing
     * ('Sedang Dikerjakan' / 'Menunggu Revisi') alih-alih selalu
     * 'Sedang Dikerjakan'.
     *
     * Sifat migration: ADDITIF.
     * - Kolom baru nullable, tidak mengubah data / kolom lain.
     * - Tidak mengubah ENUM status (daftar nilai tetap sama).
     * - Tanpa catatan status asal (NULL), command memakai fallback
     *   'Sedang Dikerjakan'.
     */
    public function up(): void
    {
        Schema::table('project_workspaces', function (Blueprint $table) {
            $table->string('overdue_previous_status', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('project_workspaces', function (Blueprint $table) {
            $table->dropColumn('overdue_previous_status');
        });
    }
};