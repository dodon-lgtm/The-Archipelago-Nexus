<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menambahkan kolom `archive_status` pada tabel `projects`.
     *
     * Nilai:
     * - 'active'   : project aktif, tampil di halaman proyek utama, masih menerima penawaran.
     * - 'archived' : project diarsipkan (histori/selesai), tampil di halaman arsip.
     * - 'inactive' : project dinonaktifkan, tidak menerima penawaran baru.
     *
     * Kolom ini TERPISAH dari `projects.status` (Open/Closed) dan
     * `project_workspaces.status`. Tidak mengubah keduanya.
     *
     * Semua project lama diberi default 'active' (aman, tidak merusak data).
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('archive_status', ['active', 'archived', 'inactive'])
                ->default('active')
                ->after('status')
                ->comment('Status arsip project: active/archived/inactive. Terpisah dari status pekerjaan.');
        });

        // Pastikan semua project lama otomatis menjadi 'active'.
        DB::table('projects')->update(['archive_status' => 'active']);
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('archive_status');
        });
    }
};
