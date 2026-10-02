<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\CompanyProfile;
use App\Models\Penawaran;
use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * REVISI Tahap Pengerjaan — test untuk fitur "Tambah Proyek" + snapshot
 * konfigurasi stage per-project ke Workspace Company & Freelancer.
 */
class CompanyProjectStageTest extends TestCase
{
    use RefreshDatabase;

    protected User $company;
    protected User $freelancer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = User::factory()->create([
            'role' => 'company',
            'phone' => '081234567890',
        ]);

        CompanyAccountRequest::create([
            'company_name' => $this->company->name,
            'contact_person' => $this->company->name,
            'company_email' => $this->company->email,
            'company_phone' => '081234567890',
            'company_address' => 'Alamat Perusahaan',
            'request_status' => 'disetujui',
        ]);

        // Profil lengkap (>80%) agar profile-check di store & selectFreelancer lolos.
        CompanyProfile::create([
            'user_id' => $this->company->id,
            'company_name' => 'PT Contoh',
            'location' => 'Jakarta',
            'company_logo' => 'logo.png',
            'description' => 'Deskripsi.',
            'website' => 'https://example.com',
            'phone' => '081234567890',
        ]);

        $this->freelancer = User::factory()->create(['role' => 'freelancer']);
    }

    private function projectPayload(array $override = []): array
    {
        return array_merge([
            'project_name' => 'Website Perusahaan',
            'project_description' => 'Membangun website perusahaan.',
            'category_id' => null,
            'budget' => 1000000,
            'deadline' => now()->addMonth()->toDateString(),
            'skills' => 'PHP, Laravel',
            'status' => Project::STATUS_OPEN,
            'stage_name' => ['Brief & Analisis', 'Pengerjaan', 'Revisi', 'Finalisasi'],
            'stage_desc' => ['Memahami kebutuhan', null, null, 'Serah terima hasil'],
        ], $override);
    }

    /** Test 10 + 11: Create project dengan beberapa stage -> project.stages benar & urutan tersimpan. */
    public function test_company_can_create_project_with_ordered_stages(): void
    {
        $response = $this->actingAs($this->company)->post('/company/projects', $this->projectPayload());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $project = Project::where('user_id', $this->company->id)->firstOrFail();

        $this->assertSame(
            ['Brief & Analisis', 'Pengerjaan', 'Revisi', 'Finalisasi'],
            $project->stageList()
        );

        $items = $project->stageItems();
        $this->assertSame('Brief & Analisis', $items[0]['name']);
        $this->assertSame('Pengerjaan', $items[1]['name']);
        $this->assertSame('Revisi', $items[2]['name']);
        $this->assertSame('Finalisasi', $items[3]['name']);
        $this->assertSame('Memahami kebutuhan', $items[0]['description']);
        $this->assertSame((int) $this->company->id, (int) $items[0]['created_by']);
    }
    /** Test 10: duplikat nama & nama kosong dilewati. */
    public function test_create_project_strips_empty_and_duplicate_stages(): void
    {
        $payload = $this->projectPayload([
            'stage_name' => ['Brief', '', 'Brief', 'Pengerjaan', '  '],
            'stage_desc' => ['a', 'b', 'c', 'd', 'e'],
        ]);

        $this->actingAs($this->company)->post('/company/projects', $payload);

        $project = Project::where('user_id', $this->company->id)->firstOrFail();
        $this->assertSame(['Brief', 'Pengerjaan'], $project->stageList());
    }

    /** Test 12: create project tanpa stage -> project->stages null (backward compatible). */
    public function test_create_project_without_stages_is_backward_compatible(): void
    {
        $payload = $this->projectPayload(['stage_name' => [], 'stage_desc' => []]);
        $this->actingAs($this->company)->post('/company/projects', $payload);

        $project = Project::where('user_id', $this->company->id)->firstOrFail();
        $this->assertNull($project->stages);
        $this->assertSame([], $project->stageItems());
    }



    /** Stage antar-project terisolasi: project A tidak memengaruhi project B. */
    public function test_project_stages_are_isolated_between_projects(): void
    {
        $projectA = Project::create([
            'user_id' => $this->company->id,
            'project_name' => 'Project A',
            'project_description' => 'Desc A',
            'budget' => 100000,
            'deadline' => now()->addDay()->toDateString(),
            'skills' => 'X',
            'status' => Project::STATUS_OPEN,
            'stages' => [['name' => 'A1', 'description' => null, 'created_by' => $this->company->id]],
        ]);

        $projectB = Project::create([
            'user_id' => $this->company->id,
            'project_name' => 'Project B',
            'project_description' => 'Desc B',
            'budget' => 200000,
            'deadline' => now()->addDay()->toDateString(),
            'skills' => 'Y',
            'status' => Project::STATUS_OPEN,
            'stages' => [['name' => 'B1', 'description' => null, 'created_by' => $this->company->id]],
        ]);

        $this->assertSame(['A1'], $projectA->stageList());
        $this->assertSame(['B1'], $projectB->stageList());
        $this->assertNotSame($projectA->stageItems(), $projectB->stageItems());
    }

    /** Workspace dibuat dari snapshot project.stages saat Company memilih freelancer. */
    public function test_workspace_seeded_from_project_stages_on_select(): void
    {
        $project = Project::create([
            'user_id' => $this->company->id,
            'project_name' => 'Website',
            'project_description' => 'Desc',
            'budget' => 1000000,
            'deadline' => now()->addMonth()->toDateString(),
            'skills' => 'PHP',
            'status' => Project::STATUS_OPEN,
            'stages' => [
                ['name' => 'Brief', 'description' => 'Analisis', 'created_by' => $this->company->id],
                ['name' => 'Pengerjaan', 'description' => null, 'created_by' => $this->company->id],
                ['name' => 'Finalisasi', 'description' => null, 'created_by' => $this->company->id],
            ],
        ]);

        $penawaran = Penawaran::create([
            'project_id' => $project->id,
            'freelancer_id' => $this->freelancer->id,
            'harga_penawaran' => 1000000,
            'estimasi_hari' => 14,
            'pesan' => 'Saya sanggup.',
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($this->company)
            ->post("/company/projects/{$project->id}/penawaran/{$penawaran->id}/select");

        $response->assertRedirect();

        $workspace = Workspace::where('project_id', $project->id)->firstOrFail();
        $this->assertSame(['Brief', 'Pengerjaan', 'Finalisasi'], $workspace->stageList());
        $this->assertSame((int) $this->freelancer->id, (int) $workspace->freelancer_id);
    }

    /** Isolasi template: mengubah stage di workspace TIDAK mengubah project.stages (snapshot). */
    public function test_company_workspace_edit_does_not_affect_project_stages(): void
    {
        $project = Project::create([
            'user_id' => $this->company->id,
            'project_name' => 'Website',
            'project_description' => 'Desc',
            'budget' => 1000000,
            'deadline' => now()->addMonth()->toDateString(),
            'skills' => 'PHP',
            'status' => Project::STATUS_OPEN,
            'stages' => [
                ['name' => 'Brief', 'description' => null, 'created_by' => $this->company->id],
                ['name' => 'Pengerjaan', 'description' => null, 'created_by' => $this->company->id],
                ['name' => 'Finalisasi', 'description' => null, 'created_by' => $this->company->id],
            ],
        ]);

        $workspace = Workspace::create([
            'project_id' => $project->id,
            'company_id' => $this->company->id,
            'freelancer_id' => $this->freelancer->id,
            'status' => 'Sedang Dikerjakan',
            'stages' => $project->stageItems(),
        ]);

        $this->actingAs($this->company)
            ->post("/company/workspaces/{$workspace->id}/progress", [
                'action' => 'rename',
                'old_stage' => 'Pengerjaan',
                'new_stage' => 'Pengerjaan & QA',
            ])->assertStatus(302);

        $workspace->refresh();
        $this->assertSame(['Brief', 'Pengerjaan & QA', 'Finalisasi'], $workspace->stageList());

        $project->refresh();
        $this->assertSame(['Brief', 'Pengerjaan', 'Finalisasi'], $project->stageList());
    }
}

