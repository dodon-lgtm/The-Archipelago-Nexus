<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Report V3 - Menambahkan kolom target ke tabel reports.
 *
 * Kolom target (nullable) diisi OTOMATIS oleh backend (source of truth),
 * BUKAN dari request pengguna. Nilai yang mungkin:
 *   - project    : laporan terhadap sebuah proyek
 *   - company    : laporan terhadap akun company
 *   - freelancer : laporan terhadap akun freelancer
 *   - website    : laporan umum / bug sistem (General Report)
 *
 * Kolom ini menghilangkan ambiguitas antara "laporan project" vs
 * "laporan user pemilik project" yang memiliki FK yang sama, dan
 * memudahkan filter admin + validasi kategori per-target.
 *
 * Bersifat additif & backward-compatible dengan Report V1/V2.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('target')
                ->nullable()
                ->after('category')
                ->comment('Target laporan: project | company | freelancer | website');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn('target');
        });
    }
};
