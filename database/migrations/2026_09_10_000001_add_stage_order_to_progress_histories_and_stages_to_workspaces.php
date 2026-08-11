<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Revision: stage-based progress.
     *
     * Perubahan:
     * 1. progress_histories.stage        : enum -> string (agar nama stage dapat dikustomisasi freelancer).
     * 2. progress_histories.stage_order  : kolom baru (posisi/urutan stage, 1-based) untuk perhitungan % server-side.
     * 3. project_workspaces.stages       : kolom baru JSON = daftar stage terurut per workspace (source of truth).
     *
     * Data lama AMAN: tidak ada penghapusan. Kolom stage_order diisi default 1 untuk baris lama,
     * dan workspace tanpa stages diisi dengan stage yang sedang/telah dipakai dari riwayat (atau default).
     */
    public function up(): void
    {
        // 1) progress_histories: stage -> string, tambah stage_order
        Schema::table('progress_histories', function (Blueprint $table) {
            $table->string('stage', 255)
                ->change();

            $table->unsignedInteger('stage_order')
                ->default(1)
                ->after('stage')
                ->comment('Urutan stage (1-based) untuk perhitungan persentase server-side');
        });

        // 2) project_workspaces: tambah stages (JSON)
        Schema::table('project_workspaces', function (Blueprint $table) {
            $table->json('stages')
                ->nullable()
                ->after('status')
                ->comment('Daftar stage custom terurut milik freelancer (source of truth)');
        });

        // 3) Backfill data yang sudah ada (tanpa menghapus data lama).
        //    Untuk setiap workspace yang belum punya stages, bangun dari stage pertama yang pernah
        //    tercatat di riwayat progres (dengan nilai default bila kosong).
        $workspaces = DB::table('project_workspaces')->get();
        foreach ($workspaces as $workspace) {
            $firstHistory = DB::table('progress_histories')
                ->where('workspace_id', $workspace->id)
                ->orderBy('created_at')
                ->orderBy('id')
                ->first();

            $stages = $firstHistory
                ? [$firstHistory->stage]
                : ['Analisis Kebutuhan'];

            DB::table('project_workspaces')
                ->where('id', $workspace->id)
                ->update(['stages' => json_encode($stages)]);
        }

        // 4) Backfill stage_order untuk riwayat yang sudah ada.
        $histories = DB::table('progress_histories')->get();
        foreach ($histories as $history) {
            $wsStages = json_decode(
                DB::table('project_workspaces')->where('id', $history->workspace_id)->value('stages') ?? '[]',
                true
            );
            $wsStages = is_array($wsStages) ? $wsStages : [];

            $order = array_search($history->stage, $wsStages, true);
            $order = ($order === false) ? 1 : ($order + 1);

            DB::table('progress_histories')
                ->where('id', $history->id)
                ->update(['stage_order' => $order]);
        }
    }

    public function down(): void
    {
        Schema::table('progress_histories', function (Blueprint $table) {
            $table->dropColumn('stage_order');
        });

        Schema::table('project_workspaces', function (Blueprint $table) {
            $table->dropColumn('stages');
        });
    }
};
