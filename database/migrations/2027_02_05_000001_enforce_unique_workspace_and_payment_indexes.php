<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * M-2A — Menegakkan integritas schema P0 hasil audit M-2:
 *   1) project_workspaces.project_id : UNIQUE(project_id)   → 1 project = 1 workspace.
 *   2) payments.workspace_id         : UNIQUE(workspace_id) → 1 workspace = 1 payment/invoice.
 *
 * LATAR BELAKANG (temuan audit M-2, F-01 & F-02):
 * Pola `$table->foreignId('x')->constrained(...)->cascadeOnDelete()->unique()`
 * pada 2026_07_29_000001_create_project_workspaces_table dan
 * 2026_08_10_000002_create_payments_table TIDAK menghasilkan index unique:
 * `->unique()` yang dirantai setelah `constrained()` di-forward ke Blueprint
 * sehingga menjadi no-op senyap. Akibatnya DB hanya memiliki index FK biasa,
 * dan migrasi berikutnya yang mencoba `dropUnique('payments_workspace_id_unique')`
 * juga tidak pernah menemukan index tersebut.
 *
 * SIFAT MIGRASI:
 *   - ADDITIF & IDEMPOTENT: hanya menambah index unique eksplisit dengan nama
 *     eksplisit; guard tableHasIndex() membuat migrasi aman dijalankan pada
 *     fresh install (SQLite/MySQL) maupun DB dev yang sudah berisi data.
 *   - TIDAK mengubah/menghapus kolom, data, FK, atau index existing.
 *   - TIDAK menghapus data duplikat: bila duplikat ditemukan, migrasi DIHENTIKAN
 *     dengan exception berisi daftar id duplikat agar diselesaikan manual.
 *
 * Catatan nullable: payments.workspace_id boleh NULL (payment kuota tanpa
 * workspace). UNIQUE pada MySQL & SQLite mengizinkan banyak baris NULL,
 * sehingga flow kuota tetap berjalan.
 */
return new class extends Migration
{
    private const PROJECT_WORKSPACES_UNIQUE = 'project_workspaces_project_id_unique';

    private const PAYMENTS_WORKSPACE_UNIQUE = 'payments_workspace_id_unique';

    public function up(): void
    {
        $this->enforceOneWorkspacePerProject();
        $this->enforceOnePaymentPerWorkspace();
    }

    public function down(): void
    {
        // Hanya melepas index yang ditambahkan migrasi ini (invers yang benar).
        // PERINGATAN: setelah rollback, jaminan 1:1 workspace ↔ payment hilang lagi.
        if (Schema::hasTable('payments') && $this->tableHasIndex('payments', self::PAYMENTS_WORKSPACE_UNIQUE)) {
            Schema::table('payments', fn (Blueprint $t) => $t->dropUnique(self::PAYMENTS_WORKSPACE_UNIQUE));
        }

        if (Schema::hasTable('project_workspaces') && $this->tableHasIndex('project_workspaces', self::PROJECT_WORKSPACES_UNIQUE)) {
            Schema::table('project_workspaces', fn (Blueprint $t) => $t->dropUnique(self::PROJECT_WORKSPACES_UNIQUE));
        }
    }

    private function enforceOneWorkspacePerProject(): void
    {
        if (!Schema::hasTable('project_workspaces')) {
            return;
        }

        if ($this->tableHasIndex('project_workspaces', self::PROJECT_WORKSPACES_UNIQUE)) {
            return; // sudah ada → jangan buat index duplikat
        }

        $this->assertNoDuplicates('project_workspaces', 'project_id');

        Schema::table('project_workspaces', function (Blueprint $t): void {
            $t->unique('project_id', self::PROJECT_WORKSPACES_UNIQUE);
        });
    }

    private function enforceOnePaymentPerWorkspace(): void
    {
        if (!Schema::hasTable('payments')) {
            return;
        }

        if ($this->tableHasIndex('payments', self::PAYMENTS_WORKSPACE_UNIQUE)) {
            return; // sudah ada → jangan buat index duplikat
        }

        $this->assertNoDuplicates('payments', 'workspace_id');

        Schema::table('payments', function (Blueprint $t): void {
            $t->unique('workspace_id', self::PAYMENTS_WORKSPACE_UNIQUE);
        });
    }

    /**
     * M-2A tidak melakukan destructive cleanup: bila ada duplikat, hentikan
     * migrasi (transactional DDL per statement) dan laporkan id-nya.
     */
    private function assertNoDuplicates(string $table, string $column): void
    {
        $duplicates = DB::table($table)
            ->select($column, DB::raw('COUNT(*) AS total'))
            ->whereNotNull($column)
            ->groupBy($column)
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($duplicates->isEmpty()) {
            return;
        }

        $ids = DB::table($table)
            ->whereIn($column, $duplicates->pluck($column)->all())
            ->orderBy($column)
            ->orderBy('id')
            ->get(['id', $column])
            ->map(fn ($row) => 'id=' . $row->id . ' ' . $column . '=' . $row->{$column})
            ->implode('; ');

        throw new \RuntimeException(
            'M-2A dihentikan: ditemukan duplikat ' . $column . ' pada tabel ' . $table
            . ' (' . $duplicates->count() . ' grup). Tidak ada data yang diubah/dihapus. '
            . 'Selesaikan duplikat berikut secara manual lalu jalankan ulang migrasi: ' . $ids
        );
    }

    private function tableHasIndex(string $table, string $index): bool
    {
        foreach (Schema::getIndexes($table) as $idx) {
            if (($idx['name'] ?? '') === $index) {
                return true;
            }
        }

        return false;
    }
};
