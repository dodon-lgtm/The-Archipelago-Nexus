<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * REVISI Tahap Pengerjaan di Project & Workspace Company.
     *
     * Tambah kolom `stages` (JSON) pada tabel `projects` sebagai konfigurasi
     * workflow MILIK project (bukan global template).
     *
     * Alasan & arsitektur:
     * - Tidak ada global template di sistem. Company menentukan daftar tahap
     *   saat membuat project. Daftar tersebut disimpan DI SINI (per project,
     *   terisolasi antar project) sehingga perubahan di project lain tidak
     *   memengaruhinya.
     * - Saat Workspace dibuat (Company memilih freelancer), snapshot dari
     *   `projects.stages` disalin ke `project_workspaces.stages` (source of
     *   truth aktif yang sudah ada dan dipakai Freelancer & Company).
     * - Format sama dengan `project_workspaces.stages`:
     *     [{"name":"...", "description":null, "created_by":<user_id>}, ...]
     *
     * Data LAMA AMAN (non-destructive):
     * - Kolom nullable; project eksisting yang belum punya nilai otomatis
     *   memakai default lama saat workspace dibuat.
     * - Workspace eksisting TIDAK disentuh (tetap bekerja dari
     *   project_workspaces.stages yang sudah ada).
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('stages')
                ->nullable()
                ->after('status')
                ->comment('Konfigurasi tahap pengerjaan milik project (snapshot saat create-project). Snapshot ini disalin ke project_workspaces.stages ketika Company memilih freelancer.');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('stages');
        });
    }
};