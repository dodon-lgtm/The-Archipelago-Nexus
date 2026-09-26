<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *  
     */
    public function up(): void
    {
        // Guard: kolom `progress` bisa saja sudah ada lebih dulu di database
        // (mis. ditambahkan oleh migrasi 2026_10_10_000000_add_progress_to_workspaces_and_stage_flags
        // yang sudah berjalan). Tanpa guard ini, `php artisan migrate` gagal
        // dengan "SQLSTATE[42S21]: Column already exists: 1060 Duplicate column name 'progress'".
        if (Schema::hasColumn('project_workspaces', 'progress')) {
            return;
        }

        Schema::table('project_workspaces', function (Blueprint $table) {
            $table->integer('progress')->default(0)->nullable()->after('stages');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('project_workspaces', 'progress')) {
            return;
        }

        Schema::table('project_workspaces', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};
