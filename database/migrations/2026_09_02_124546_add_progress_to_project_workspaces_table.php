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
        Schema::table('project_workspaces', function (Blueprint $table) {
            // M-2C — JANGAN memakai ->after('stages').
            //
            // Kolom `stages` baru dibuat oleh migration 2026_09_10_000001
            // (add_stage_order_to_progress_histories_and_stages_to_workspaces), yang
            // pada chain urut-nama-file berjalan SETELAH migration ini. Akibatnya
            // fresh install MySQL gagal dengan:
            //   SQLSTATE[42S22] Unknown column 'stages' in 'project_workspaces'
            // (Pada database dev lama dulu tidak gagal karena migration ini kebetulan
            // dijalankan belakangan — tercatat batch 18, sedangkan stages batch 3.)
            //
            // Anchor diganti ke `status`, satu-satunya kolom yang pasti sudah ada pada
            // titik ini (dibuat oleh create table dan tidak pernah di-drop/rename).
            // Karena 2026_09_10 menambahkan `stages` dengan ->after('status'), urutan
            // kolom final tetap identik dengan database existing: status, stages, progress.
            //
            // `stages` TETAP menjadi bagian schema (tidak dihapus / tidak dihidupkan dini)
            // dan tetap dipakai aplikasi (Workspace model + WorkspaceController).
            $table->integer('progress')->default(0)->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('project_workspaces', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};
