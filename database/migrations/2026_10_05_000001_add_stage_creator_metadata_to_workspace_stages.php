<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * PERLUASAN tahap pengerjaan (bukan tabel baru): kolom yang SAMA dipakai,
     * yaitu project_workspaces.stages (JSON).
     *
     * Sebelum : ["Analisis Kebutuhan", "UI Design"]                     (array of strings)
     * Sesudah : [{"name":"...","description":null,"created_by":<user_id>}] (array of objects)
     *
     * Data LAMA AMAN: setiap entry string dikonversi ke object dengan
     * `created_by` = freelancer_id (sebelum fitur ini, hanya freelancer
     * yang dapat membuat tahap). Entry yang SUDAH berbentuk object (baru)
     * dibiarkan apa adanya.
     */
    public function up(): void
    {
        $workspaces = DB::table('project_workspaces')
            ->select('id', 'stages', 'freelancer_id')
            ->get();

        foreach ($workspaces as $workspace) {
            if ($workspace->stages === null) {
                continue;
            }

            $raw = json_decode((string) $workspace->stages, true);
            if (!is_array($raw) || count($raw) === 0) {
                continue;
            }

            $converted = [];
            foreach ($raw as $entry) {
                // Sudah berbentuk object (hasil fitur baru) -> biarkan.
                if (is_array($entry) && isset($entry['name'])) {
                    $converted[] = [
                        'name' => (string) $entry['name'],
                        'description' => isset($entry['description']) && $entry['description'] !== ''
                            ? (string) $entry['description']
                            : null,
                        'created_by' => isset($entry['created_by'])
                            ? (int) $entry['created_by']
                            : (int) $workspace->freelancer_id,
                    ];
                    continue;
                }

                // String lama -> object milik freelancer workspace ini.
                $converted[] = [
                    'name' => (string) $entry,
                    'description' => null,
                    'created_by' => (int) $workspace->freelancer_id,
                ];
            }

            if (count($converted) === 0) {
                continue;
            }

            DB::table('project_workspaces')
                ->where('id', $workspace->id)
                ->update(['stages' => json_encode($converted)]);
        }
    }

    public function down(): void
    {
        // Balikan object -> array of strings (best-effort, urutan dipertahankan).
        $workspaces = DB::table('project_workspaces')
            ->select('id', 'stages')
            ->get();

        foreach ($workspaces as $workspace) {
            if ($workspace->stages === null) {
                continue;
            }

            $raw = json_decode((string) $workspace->stages, true);
            if (!is_array($raw) || count($raw) === 0) {
                continue;
            }

            $names = [];
            foreach ($raw as $entry) {
                if (is_array($entry)) {
                    $names[] = (string) ($entry['name'] ?? '');
                } else {
                    $names[] = (string) $entry;
                }
            }

            DB::table('project_workspaces')
                ->where('id', $workspace->id)
                ->update(['stages' => json_encode($names)]);
        }
    }
};