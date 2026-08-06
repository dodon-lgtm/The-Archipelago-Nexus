<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Report V2 - Menambahkan kolom baru ke tabel reports.
     *
     * Kolom baru:
     * - category    : Kategori/tipe report (contoh: umum, penipuan, konten-tidak-pantas, dll)
     * - handled_by  : Admin yang menangani laporan (FK ke users, nullable)
     * - resolved_at : Waktu laporan diselesaikan (nullable)
     *
     * Semua kolom bersifat nullable/default sehingga kompatibel dengan
     * data & workflow Report V1 yang sudah berjalan.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('category')
                ->default('umum')
                ->after('description')
                ->comment('Kategori/tipe report (umum, penipuan, dll)');

            $table->foreignId('handled_by')
                ->nullable()
                ->after('admin_note')
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Admin yang menangani laporan');

            $table->timestamp('resolved_at')
                ->nullable()
                ->after('handled_by')
                ->comment('Waktu laporan diselesaikan');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('handled_by');
            $table->dropColumn(['category', 'resolved_at']);
        });
    }
};

