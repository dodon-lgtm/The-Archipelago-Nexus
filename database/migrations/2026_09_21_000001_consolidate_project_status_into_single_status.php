<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Konsolidasi status proyek menjadi SATU kolom `projects.status`.
 *
 * Sebelumnya status proyek terpecah di dua kolom:
 *   - projects.status        : ENUM('Open','Closed')  -> Open / Closed
 *   - projects.archive_status: ENUM('active','archived','inactive') -> arsip/nonaktif
 *
 * Penyebab bug "Open/Tutup/Archive tercampur": dua kolom dengan makna
 * tumpang-tindih yang harus dijaga konsisten satu sama lain.
 *
 * Setelah migrasi ini HANYA ada 3 nilai standar di `projects.status`:
 *   - 'open'     : proyek aktif, menerima penawaran baru.
 *   - 'closed'   : proyek ditutup Company, tidak menerima penawaran baru,
 *                  tetap tersimpan dan tetap bisa dilihat (bukan arsip).
 *   - 'archived' : proyek masuk arsip, tidak menerima penawaran baru.
 *
 * Pemetaan data lama:
 *   status='Open'  AND archive_status='active'   -> 'open'
 *   status='Closed' AND archive_status='active'  -> 'closed'
 *   archive_status='archived'                    -> 'archived'
 *   archive_status='inactive'                    -> 'closed' (perilakunya sama: tidak menerima penawaran)
 *
 * CATATAN: MySQL menolak ENUM dengan nilai duplikat (case-insensitive),
 * jadi kolom diubah ke VARCHAR dulu, baru dipersempit ke ENUM final.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah kolom menjadi VARCHAR agar bisa menampung nilai lama & baru
        //    tanpa bentrok dengan aturan ENUM MySQL.
                if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE projects MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'open'"
            );
        }

        // 2. Migrasi data lama ke nilai standar baru (source of truth: archive_status).
        DB::table('projects')->where('archive_status', 'archived')->update(['status' => 'archived']);
        DB::table('projects')->where('archive_status', 'inactive')->update(['status' => 'closed']);
        DB::table('projects')->where('status', 'Open')->update(['status' => 'open']);
        DB::table('projects')->where('status', 'Closed')->update(['status' => 'closed']);

        // 3. Persempit kolom menjadi ENUM 3 nilai standar.
                if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE projects MODIFY COLUMN status ENUM('open','closed','archived') NOT NULL DEFAULT 'open'"
            );
        }

        // 4. Hapus kolom archive_status (tidak lagi diperlukan).
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('archive_status');
        });
    }

    public function down(): void
    {
        // 1. Tambahkan kembali kolom archive_status.
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('archive_status', ['active', 'archived', 'inactive'])
                ->default('active')
                ->after('status');
        });

        // 2. Longgarkan kolom status menjadi VARCHAR agar nilai lowercase bisa ditimpa.
                if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE projects MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'Open'"
            );
        }

        // 3. Pulihkan data lama.
        DB::table('projects')->where('status', 'archived')->update(['status' => 'Closed', 'archive_status' => 'archived']);
        DB::table('projects')->where('status', 'closed')->update(['status' => 'Closed', 'archive_status' => 'active']);
        DB::table('projects')->where('status', 'open')->update(['status' => 'Open', 'archive_status' => 'active']);

        // 4. Kembalikan ENUM lama.
                if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE projects MODIFY COLUMN status ENUM('Open','Closed') NOT NULL DEFAULT 'Open'"
            );
        }
    }
};
