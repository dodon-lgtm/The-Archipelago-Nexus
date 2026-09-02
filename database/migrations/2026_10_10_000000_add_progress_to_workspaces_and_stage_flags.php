<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom progress (int 0-100) ke project_workspaces.
     * Backfill flag is_completed di JSON stages untuk data legacy
     * berdasarkan progress_history terakhir (legacy linear).
     * Tidak menghapus data lama.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('project_workspaces', 'progress')) {
            Schema::table('project_workspaces', function (Blueprint $table) {
                $table->unsignedTinyInteger('progress')
                    ->default(0)
                    ->after('status')
                    ->comment('Progress keseluruhan 0-100 dihitung fleksibel: completed/total*100');
            });
        }

        // Backfill progress dan flag is_completed untuk workspace legacy
        $workspaces = DB::table('project_workspaces')->get();
        foreach ($workspaces as $ws) {
            $stagesRaw = $ws->stages;
            $stages = is_string($stagesRaw) ? json_decode($stagesRaw, true) : $stagesRaw;
            if (!is_array($stages) || count($stages) === 0) {
                $stages = [['name' => 'Analisis Kebutuhan']];
            }

            // Normalisasi ke array of associative
            $normalized = [];
            foreach (array_values($stages) as $entry) {
                if (is_array($entry)) {
                    $normalized[] = $entry;
                } else {
                    $normalized[] = ['name' => (string) $entry];
                }
            }

            // Jika sudah punya flag is_completed, hitung progress fleksibel
            $hasFlag = false;
            foreach ($normalized as $it) {
                if (array_key_exists('is_completed', $it)) {
                    $hasFlag = true;
                    break;
                }
            }

            if ($hasFlag) {
                $completed = 0;
                foreach ($normalized as $it) {
                    if (!empty($it['is_completed'])) $completed++;
                }
                $total = count($normalized);
                $progress = $total > 0 ? (int) round(($completed / $total) * 100) : 0;
            } else {
                // Legacy: gunakan stage_order terakhir
                $latest = DB::table('progress_histories')
                    ->where('workspace_id', $ws->id)
                    ->orderByDesc('created_at')
                    ->orderByDesc('id')
                    ->first();
                $order = $latest ? (int) ($latest->stage_order ?? 0) : 0;
                $total = count($normalized);

                // Tandai N tahap pertama sebagai selesai
                if ($order > 0 && $total > 0) {
                    $clamped = min($order, $total);
                    for ($i = 0; $i < $clamped; $i++) {
                        $normalized[$i]['is_completed'] = true;
                        $normalized[$i]['note'] = $normalized[$i]['note'] ?? ($latest->description ?? null);
                        $normalized[$i]['completed_at'] = $normalized[$i]['completed_at'] ?? ($latest->created_at ?? now());
                        $normalized[$i]['completed_by'] = $normalized[$i]['completed_by'] ?? ($latest->updated_by ?? null);
                    }
                    for ($i = $clamped; $i < $total; $i++) {
                        $normalized[$i]['is_completed'] = false;
                        $normalized[$i]['note'] = $normalized[$i]['note'] ?? null;
                        $normalized[$i]['completed_at'] = $normalized[$i]['completed_at'] ?? null;
                        $normalized[$i]['completed_by'] = $normalized[$i]['completed_by'] ?? null;
                    }
                    if ($clamped >= $total) {
                        $progress = 100;
                    } else {
                        $progress = (int) round(($clamped / $total) * 100);
                    }
                } else {
                    // Belum mulai
                    foreach ($normalized as $idx => $it) {
                        $normalized[$idx]['is_completed'] = false;
                        $normalized[$idx]['note'] = $normalized[$idx]['note'] ?? null;
                        $normalized[$idx]['completed_at'] = null;
                        $normalized[$idx]['completed_by'] = null;
                    }
                    $progress = 0;
                }
            }

            // Pastikan semua entry memiliki keys lengkap
            foreach ($normalized as $idx => $it) {
                $normalized[$idx]['is_completed'] = (bool) ($it['is_completed'] ?? false);
                $normalized[$idx]['note'] = $it['note'] ?? null;
                $normalized[$idx]['completed_at'] = $it['completed_at'] ?? null;
                $normalized[$idx]['completed_by'] = $it['completed_by'] ?? null;
                // pastikan description & created_by tetap ada
                if (!isset($normalized[$idx]['description'])) $normalized[$idx]['description'] = null;
                if (!isset($normalized[$idx]['created_by'])) $normalized[$idx]['created_by'] = null;
            }

            DB::table('project_workspaces')
                ->where('id', $ws->id)
                ->update([
                    'stages' => json_encode($normalized),
                    'progress' => $progress,
                ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('project_workspaces', 'progress')) {
            Schema::table('project_workspaces', function (Blueprint $table) {
                $table->dropColumn('progress');
            });
        }
    }
};
