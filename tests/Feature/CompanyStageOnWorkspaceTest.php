<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\ProgressHistory;
use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyStageOnWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    protected User $company;
    protected User $freelancer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = User::factory()->create(['role' => 'company']);
        // CompanyAccountRequest wajib agar middleware ensureCompanyAdminOrAbort tidak dajan 403.
        CompanyAccountRequest::create([
            'company_name' => $this->company->name,
            'contact_person' => $this->company->name,
            'company_email' => $this->company->email,
            'company_phone' => '081234567890',
            'company_address' => 'Alamat Perusahaan',
            'request_status' => 'disetujui',
        ]);

        $this->freelancer = User::factory()->create(['role' => 'freelancer']);
    }

    private function createWorkspace(): Workspace
    {
        $project = Project::factory()->create([
            'user_id' => $this->company->id,
            'status' => Project::STATUS_OPEN,
        ]);

        return Workspace::create([
            'project_id' => $project->id,
            'company_id' => $this->company->id,
            'freelancer_id' => $this->freelancer->id,
            'status' => 'Sedang Dikerjakan',
        ]);
    }

    /** LANGKAH 14 â€¢ Test 1+2: Company tambah tahap -> DB tersimpan + urutan + ownership. */
    public function test_company_can_add_stage_to_own_workspace(): void
    {
        $workspace = $this->createWorkspace();

        $response = $this->actingAs($this->company)
            ->post("/company/workspaces/{$workspace->id}/progress", [
                'action' => 'add',
                'new_stage' => 'Integrasi Pembayaran',
                'description' => 'Integrasi gateway pembayaran end-to-end.',
            ]);

        $response->assertStatus(302);

        $workspace->refresh();
        $items = $workspace->stageItems();
        $names = array_map(fn ($item) => $item['name'], $items);

        $this->assertTrue(in_array('Analisis Kebutuhan', $names, true));
        $this->assertTrue(in_array('Integrasi Pembayaran', $names, true));

        // Urutan: tahap Company dijono last+1 (default 'Analisis Kebutuhan', then integrasi).
        $this->assertSame('Analisis Kebutuhan', $items[0]['name']);
        $this->assertSame('Integrasi Pembayaran', $items[1]['name']);

        // Ownership: pembuat = company + deskripsi.
        $this->assertSame((int) $this->company->id, (int) $items[1]['created_by']);
        $this->assertSame('Integrasi gateway pembayaran end-to-end.', $items[1]['description']);
    }

    /** LANGKAH 14 â€¢ Test 3: Refresh workspace -> tahap tetap muncul (render blade Company). */
    public function test_company_workspace_renders_and_shows_add_button(): void
    {
        $workspace = $this->createWorkspace();
        $this->actingAs($this->company)
            ->post("/company/workspaces/{$workspace->id}/progress", [
                'action' => 'add',
                'new_stage' => 'Integrasi Pembayaran',
            ])->assertStatus(302);

        $response = $this->actingAs($this->company)
            ->get("/company/workspaces/{$workspace->id}");

        $response->assertOk();
        $this->assertStringContainsString('Tambah Tahap', (string) $response->getContent());
    }

    /** LANGKAH 14 â€¢ Test 4: Freelancer membuka workspace sama -> tahap Company terlihat. */
    public function test_freelancer_sees_company_stage(): void
    {
        $workspace = $this->createWorkspace();
        $this->actingAs($this->company)
            ->post("/company/workspaces/{$workspace->id}/progress", [
                'action' => 'add',
                'new_stage' => 'Integrasi Pembayaran',
            ]);

        $response = $this->actingAs($this->freelancer)
            ->get("/freelancer/workspaces/{$workspace->id}");

        $response->assertOk();
        $this->assertStringContainsString('Integrasi Pembayaran', (string) $response->getContent());
        $this->assertStringContainsString('Dibuat oleh', (string) $response->getContent());
    }
/** LANGKAH 14 â€¢ Test 5: Freelancer mencoba edit tahap Company -> 403. */
    public function test_freelancer_cannot_edit_company_stage(): void
    {
        $workspace = $this->createWorkspace();
        $this->actingAs($this->company)
            ->post("/company/workspaces/{$workspace->id}/progress", [
                'action' => 'add',
                'new_stage' => 'Integrasi Pembayaran',
            ]);

        $response = $this->actingAs($this->freelancer)
            ->post("/freelancer/workspaces/{$workspace->id}/progress", [
                'action' => 'rename',
                'old_stage' => 'Integrasi Pembayaran',
                'new_stage' => 'Hacked Stage',
            ]);

        $response->assertStatus(403);
    }

    /** LANGKAH 14 (REVISI) - Test 6: Company pemilik boleh mengedit tahap freelancer. */
    public function test_company_can_edit_freelancer_stage_in_own_workspace(): void
    {
        $workspace = $this->createWorkspace();
        $this->actingAs($this->freelancer)
            ->post("/freelancer/workspaces/{$workspace->id}/progress", [
                'action' => 'add',
                'new_stage' => 'UI Design',
            ]);

        $response = $this->actingAs($this->company)
            ->post("/company/workspaces/{$workspace->id}/progress", [
                'action' => 'rename',
                'old_stage' => 'UI Design',
                'new_stage' => 'UI Design Revisi',
            ]);

        $response->assertStatus(302);

        $workspace->refresh();
        $names = array_map(fn ($item) => $item['name'], $workspace->stageItems());
        $this->assertTrue(in_array('UI Design Revisi', $names, true));
        $this->assertFalse(in_array('UI Design', $names, true));
    }

    /** REVISI: Company pemilik boleh menghapus tahap yang dibuat freelancer. */
    public function test_company_can_delete_freelancer_stage(): void
    {
        $workspace = $this->createWorkspace();
        $this->actingAs($this->freelancer)
            ->post("/freelancer/workspaces/{$workspace->id}/progress", [
                'action' => 'add',
                'new_stage' => 'UI Design',
            ]);

        $response = $this->actingAs($this->company)
            ->post("/company/workspaces/{$workspace->id}/progress", [
                'action' => 'delete',
                'old_stage' => 'UI Design',
            ]);

        $response->assertStatus(302);

        $workspace->refresh();
        $names = array_map(fn ($item) => $item['name'], $workspace->stageItems());
        $this->assertFalse(in_array('UI Design', $names, true));
    }

    /** REVISI: Company A TIDAK boleh mengubah tahap workspace Project Company B. */
    public function test_company_cannot_modify_other_company_workspace(): void
    {
        $otherCompany = User::factory()->create(['role' => 'company']);

        $otherProject = Project::factory()->create([
            'user_id' => $otherCompany->id,
            'status' => Project::STATUS_OPEN,
        ]);

        $otherWorkspace = Workspace::create([
            'project_id' => $otherProject->id,
            'company_id' => $otherCompany->id,
            'freelancer_id' => $this->freelancer->id,
            'status' => 'Sedang Dikerjakan',
        ]);

        $response = $this->actingAs($this->company)
            ->post("/company/workspaces/{$otherWorkspace->id}/progress", [
                'action' => 'add',
                'new_stage' => 'Hacked Stage',
            ]);

        $response->assertStatus(403);

        $otherWorkspace->refresh();
        $names = array_map(fn ($item) => $item['name'], $otherWorkspace->stageItems());
        $this->assertFalse(in_array('Hacked Stage', $names, true));
    }

    /** LANGKAH 14 â€¢ Test 7: Freelancer masih dapat tambah tahap sendiri. */
    public function test_freelancer_still_adds_stage(): void
    {
        $workspace = $this->createWorkspace();

        $this->actingAs($this->freelancer)
            ->post("/freelancer/workspaces/{$workspace->id}/progress", [
                'action' => 'add',
                'new_stage' => 'UI Design',
            ]);

        $workspace->refresh();
        $names = array_map(fn ($item) => $item['name'], $workspace->stageItems());
        $this->assertTrue(in_array('UI Design', $names, true));

        // Tahap milik freelancer.
        $item = collect($workspace->stageItems())->firstWhere('name', 'UI Design');
        $this->assertSame((int) $this->freelancer->id, (int) $item['created_by']);
    }

    /** LANGKAH 14 â€¢ Test 8: progress/urutan lama aman + append company tahap last+1. */
    public function test_existing_progress_remaining_safe_after_company_adds_stage(): void
    {
        $workspace = $this->createWorkspace();

        $this->actingAs($this->freelancer)
            ->post("/freelancer/workspaces/{$workspace->id}/progress", [
                'action' => 'add',
                'new_stage' => 'UI Design',
            ]);

        $this->actingAs($this->company)
            ->post("/company/workspaces/{$workspace->id}/progress", [
                'action' => 'add',
                'new_stage' => 'Integrasi Pembayaran',
            ]);

        $workspace->refresh();

        // Tahap Freelancer lama masuk terahap selbe, Company appended last.
        $names = array_map(fn ($item) => $item['name'], $workspace->stageItems());
        $this->assertSame('UI Design', $names[1]);
        $this->assertSame('Integrasi Pembayaran', $names[2]);

                $this->assertSame(3, $workspace->totalStages());
        $this->assertSame(100, $workspace->calculateProgressForStage(3));
    }

    /**
     * LANGKAH 15 â€¢ Test 8: workspace baru (progress belum mulai) tetap 0% sampai
     * freelancer/Company memilih tahap â€” bukan langsung 100%.
     *
     * Reproduksi alur pembuatan nyata (Company/ProjectController): workspace
     * dibuat dengan 5 tahap serta catatan ProgressHistory awal di `stage_order = 0`.
     */
    public function test_newly_created_workspace_starts_at_zero_until_stage_selected(): void
    {
        $project = Project::factory()->create([
            'user_id' => $this->company->id,
            'status' => Project::STATUS_OPEN,
        ]);

                $workspace = Workspace::create([
            'project_id' => $project->id,
            'company_id' => $this->company->id,
            'freelancer_id' => $this->freelancer->id,
            'status' => 'Sedang Dikerjakan',
            'stages' => ['Analisis Kebutuhan', 'Desain', 'Backend', 'Frontend', 'Testing'],
        ]);

        // Alur pembuatan nyata: seed catatan progres awal di stage_order = 0 (belum mulai).
        ProgressHistory::create([
            'workspace_id' => $workspace->id,
            'stage' => 'Dipilih',
            'stage_order' => 0,
            'progress' => 0,
            'description' => 'Freelancer dipilih. Menunggu pembayaran.',
            'updated_by' => $this->company->id,
        ]);

        // Persyaratan: workspace baru WAJIB 0%, bukan 100%.
        $this->assertSame(0, $workspace->currentProgress());
        $this->assertSame(0, $workspace->calculateProgressForStage(0));
        $this->assertSame(5, $workspace->totalStages());

        // Halaman detail (Company) harus merender progress bar di 0% pada server-side.
        $response = $this->actingAs($this->company)
            ->get("/company/workspaces/{$workspace->id}");
        $response->assertStatus(200);
        // Main progress bar + modal preview bar keduanya lebarnya 0%.
        // (Jika $progressValue pernah jadi 100, assertion ini gagal karena lebar jadi 'width: 100%'.)
        $response->assertSee('style="width: 0%"', false);

        // "Naik sesuai jumlah tahap pengerjaan": stage ke-3 dari 5 = 60%;
        // stage terakhir (ke-5) = 100%.
        $this->assertSame(60, $workspace->calculateProgressForStage(3));
        $this->assertSame(100, $workspace->calculateProgressForStage(5));
    }
}
