<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * M-2A — Regression/schema invariant test hasil audit M-2 (F-01 & F-02).
 *
 * Tujuan: memastikan index UNIQUE benar-benar TERBENTUK di database, bukan
 * hanya diasumsikan dari kode migrasi. Kelas bug yang dicegah: pola
 * `->foreignId('x')->constrained(...)->cascadeOnDelete()->unique()` pada
 * 2026_07_29_000001 & 2026_08_10_000002 yang `->unique()`-nya menjadi no-op,
 * sehingga DB hanya punya index FK biasa tanpa UNIQUE.
 *
 * Test memeriksa introspection schema (Schema::getIndexes) DAN perilaku
 * database terhadap duplikat. Assertion tidak bergantung pada pesan error
 * driver tertentu — hanya pada kelas exception constraint violation.
 */
class SchemaIntegrityTest extends TestCase
{
    use RefreshDatabase;

    private const PROJECT_WORKSPACES_UNIQUE = 'project_workspaces_project_id_unique';

    private const PAYMENTS_WORKSPACE_UNIQUE = 'payments_workspace_id_unique';

    // ─────────────────────────── SCHEMA INTROSPECTION ───────────────────────────

    public function test_project_workspaces_has_unique_index_on_project_id(): void
    {
        $index = $this->findIndex('project_workspaces', self::PROJECT_WORKSPACES_UNIQUE);

        $this->assertNotNull(
            $index,
            'Index UNIQUE ' . self::PROJECT_WORKSPACES_UNIQUE . ' tidak ditemukan pada project_workspaces.'
        );
        $this->assertTrue((bool) $index['unique'], self::PROJECT_WORKSPACES_UNIQUE . ' harus UNIQUE.');
        $this->assertSame(['project_id'], array_values($index['columns']));
    }

    public function test_payments_has_unique_index_on_workspace_id(): void
    {
        $index = $this->findIndex('payments', self::PAYMENTS_WORKSPACE_UNIQUE);

        $this->assertNotNull(
            $index,
            'Index UNIQUE ' . self::PAYMENTS_WORKSPACE_UNIQUE . ' tidak ditemukan pada payments.'
        );
        $this->assertTrue((bool) $index['unique'], self::PAYMENTS_WORKSPACE_UNIQUE . ' harus UNIQUE.');
        $this->assertSame(['workspace_id'], array_values($index['columns']));
    }

    /** Jangan sampai ada index unique ganda pada kolom integritas yang sama. */
    public function test_each_integrity_column_has_exactly_one_unique_index(): void
    {
        $this->assertCount(
            1,
            $this->uniqueIndexesOn('project_workspaces', 'project_id'),
            'project_workspaces.project_id harus memiliki TEPAT SATU index unique.'
        );

        $this->assertCount(
            1,
            $this->uniqueIndexesOn('payments', 'workspace_id'),
            'payments.workspace_id harus memiliki TEPAT SATU index unique.'
        );
    }

    /** Index existing yang tidak boleh hilang karena migrasi M-2A. */
    public function test_existing_payments_and_workspaces_indexes_are_preserved(): void
    {
        $invoiceUnique = $this->findIndex('payments', 'payments_invoice_number_unique');
        $this->assertNotNull($invoiceUnique, 'payments_invoice_number_unique hilang.');
        $this->assertTrue((bool) $invoiceUnique['unique']);

        $midtransUnique = $this->findIndex('payments', 'payments_midtrans_transaction_id_unique');
        $this->assertNotNull($midtransUnique, 'payments_midtrans_transaction_id_unique hilang.');
        $this->assertTrue((bool) $midtransUnique['unique']);

        $this->assertNotNull($this->findIndex('payments', 'payments_payment_type_index'), 'payments_payment_type_index hilang.');
        $this->assertNotNull($this->findIndex('payments', 'payments_funds_status_index'), 'payments_funds_status_index hilang.');
        $this->assertNotNull($this->findIndex('project_workspaces', 'primary'));
        $this->assertNotNull($this->findIndex('payments', 'primary'));
    }

    // ─────────────────────────── BEHAVIOR (DB CONSTRAINT) ───────────────────────

    /** 1 project = 1 workspace: workspace kedua untuk project yang sama ditolak DB. */
    public function test_second_workspace_for_same_project_is_rejected_by_database(): void
    {
        $company = User::factory()->create(['role' => 'company']);
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $project = Project::factory()->create(['user_id' => $company->id]);

        $this->createWorkspace($project->id, $company->id, $freelancer->id);

        $failed = false;

        try {
            $this->createWorkspace($project->id, $company->id, $freelancer->id);
        } catch (QueryException $e) {
            $failed = true;
        }

        $this->assertTrue($failed, 'Insert workspace kedua untuk project yang sama seharusnya gagal (constraint violation).');
        $this->assertSame(1, Workspace::where('project_id', $project->id)->count());

        // Pastikan constraint tidak over-restrictive: project lain tetap boleh punya workspace.
        $otherProject = Project::factory()->create(['user_id' => $company->id]);
        $this->createWorkspace($otherProject->id, $company->id, $freelancer->id);

        $this->assertSame(1, Workspace::where('project_id', $otherProject->id)->count());
    }

    /** 1 workspace = 1 payment: payment kedua untuk workspace yang sama ditolak DB. */
    public function test_second_payment_for_same_workspace_is_rejected_by_database(): void
    {
        $company = User::factory()->create(['role' => 'company']);
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $project = Project::factory()->create(['user_id' => $company->id]);
        $workspace = $this->createWorkspace($project->id, $company->id, $freelancer->id);

        $this->createWorkspacePayment($workspace->id, $company->id, $freelancer->id, 'INV-M2A-FIRST-' . uniqid());

        $failed = false;

        try {
            // Nomor invoice berbeda supaya kegagalan pasti berasal dari UNIQUE(workspace_id).
            $this->createWorkspacePayment($workspace->id, $company->id, $freelancer->id, 'INV-M2A-SECOND-' . uniqid());
        } catch (QueryException $e) {
            $failed = true;
        }

        $this->assertTrue($failed, 'Insert payment kedua untuk workspace yang sama seharusnya gagal (constraint violation).');
        $this->assertSame(1, Payment::where('workspace_id', $workspace->id)->count());

        // Workspace lain tetap boleh memiliki payment sendiri.
        $otherProject = Project::factory()->create(['user_id' => $company->id]);
        $otherWorkspace = $this->createWorkspace($otherProject->id, $company->id, $freelancer->id);
        $this->createWorkspacePayment($otherWorkspace->id, $company->id, $freelancer->id, 'INV-M2A-OTHER-' . uniqid());

        $this->assertSame(1, Payment::where('workspace_id', $otherWorkspace->id)->count());
    }

    /**
     * Payment kuota (workspace_id NULL) tetap boleh banyak baris: UNIQUE pada
     * kolom nullable tidak boleh memblokir flow kuota existing.
     */
    public function test_multiple_quota_payments_with_null_workspace_id_are_allowed(): void
    {
        $company = User::factory()->create(['role' => 'company']);

        foreach ([1, 2, 3] as $n) {
            Payment::create([
                'workspace_id' => null,
                'company_id' => $company->id,
                'freelancer_id' => null,
                'invoice_number' => 'INV-QOT-M2A-' . $n . '-' . uniqid(),
                'amount' => 10000.00,
                'payment_type' => Payment::PAYMENT_TYPE_QUOTA,
                'status' => 'pending',
                'payment_method' => 'Midtrans',
            ]);
        }

        $this->assertSame(3, Payment::where('company_id', $company->id)->whereNull('workspace_id')->count());
    }

    // ──────────────────────────────── HELPERS ──────────────────────────────────

    private function findIndex(string $table, string $index): ?array
    {
        foreach (Schema::getIndexes($table) as $idx) {
            if (($idx['name'] ?? null) === $index) {
                return $idx;
            }
        }

        return null;
    }

    /** @return array<int, array<string, mixed>> */
    private function uniqueIndexesOn(string $table, string $column): array
    {
        return array_values(array_filter(
            Schema::getIndexes($table),
            fn ($idx) => !empty($idx['unique']) && array_values($idx['columns']) === [$column]
        ));
    }

    private function createWorkspace(int $projectId, int $companyId, int $freelancerId): Workspace
    {
        return Workspace::create([
            'project_id' => $projectId,
            'company_id' => $companyId,
            'freelancer_id' => $freelancerId,
            // Catatan: ENUM status di SQLite mengikuti daftar migration awal.
            'status' => 'Sedang Dikerjakan',
        ]);
    }

    private function createWorkspacePayment(int $workspaceId, int $companyId, int $freelancerId, string $invoiceNumber): Payment
    {
        return Payment::create([
            'workspace_id' => $workspaceId,
            'company_id' => $companyId,
            'freelancer_id' => $freelancerId,
            'invoice_number' => $invoiceNumber,
            'amount' => 1050000.00,
            'platform_fee' => 50000.00,
            'platform_fee_rate' => 5.00,
            'freelancer_receive' => 1000000.00,
            'status' => 'pending',
        ]);
    }
}
