<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature test untuk section "Pekerjaan Aktif" pada dashboard freelancer.
 *
 * Memvalidasi:
 * - query ownership (HANYA workspace milik freelancer yang login),
 * - maksimal 3 workspace,
 * - status 'Selesai' tidak ikut ditampilkan,
 * - progress/tahap memakai method model existing (count-based),
 * - indikator overdue dari status existing,
 * - link ke halaman workspace existing (freelancer.workspaces.show).
 */
class FreelancerDashboardActiveWorkspacesTest extends TestCase
{
    use RefreshDatabase;

    private User $freelancer;
    private User $otherFreelancer;
    private User $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->freelancer = User::factory()->create(['role' => 'freelancer']);
        $this->otherFreelancer = User::factory()->create(['role' => 'freelancer']);
        $this->company = User::factory()->create(['role' => 'company']);
    }

    private function makeWorkspace(
        User $freelancer,
        string $projectName,
        string $status,
        ?string $deadline = null,
        array $stages = []
    ): Workspace {
        $project = Project::factory()->create([
            'user_id' => $this->company->id,
            'project_name' => $projectName,
            'deadline' => $deadline,
            'status' => Project::STATUS_OPEN,
        ]);

        return Workspace::create([
            'project_id' => $project->id,
            'company_id' => $this->company->id,
            'freelancer_id' => $freelancer->id,
            'status' => $status,
            'stages' => $stages,
        ]);
    }

    private function completedStages(): array
    {
        return [
            ['name' => 'Analisis', 'description' => null, 'created_by' => $this->freelancer->id, 'is_completed' => true],
            ['name' => 'Implementasi', 'description' => null, 'created_by' => $this->freelancer->id, 'is_completed' => false],
        ];
    }

    public function test_dashboard_shows_empty_state_without_active_workspace(): void
    {
        $response = $this->actingAs($this->freelancer)->get('/freelancer/dashboard');

        $response->assertOk();
        $response->assertSee('Pekerjaan Aktif');
        $response->assertSee('Belum ada pekerjaan aktif');
    }

    public function test_dashboard_shows_active_workspace_card_with_progress_deadline_and_link(): void
    {
        $workspace = $this->makeWorkspace(
            $this->freelancer,
            'Website Portofolio',
            'Sedang Dikerjakan',
            now()->addDays(7)->toDateString(),
            $this->completedStages()
        );

        $response = $this->actingAs($this->freelancer)->get('/freelancer/dashboard');

        $response->assertOk();
        $response->assertSee('Website Portofolio');            // nama proyek
        $response->assertSee($this->company->name);            // nama company
        $response->assertSee('Sedang Dikerjakan');             // status existing
        $response->assertSee('50%');                           // progress 1/2 tahap (count-based)
        $response->assertSee('style="width: 50%"', false);     // progress bar
        $response->assertSee('Deadline:');                     // deadline proyek
        $response->assertSee('1/2 tahap');                     // tahap selesai / total
        $response->assertSee('Lihat Pekerjaan');               // CTA
        $response->assertSee(route('freelancer.workspaces.show', $workspace), false);
    }

    public function test_dashboard_marks_overdue_workspace_with_indicator(): void
    {
        $this->makeWorkspace(
            $this->freelancer,
            'Aplikasi Kasir',
            'Melewati Batas Waktu',
            now()->subDays(3)->toDateString(),
            $this->completedStages()
        );

        $response = $this->actingAs($this->freelancer)->get('/freelancer/dashboard');

        $response->assertOk();
        $response->assertSee('Aplikasi Kasir');
        $response->assertSee('Melewati Batas Waktu');
        $response->assertSee('Terlambat');
    }

    public function test_dashboard_only_lists_own_active_workspaces_and_limits_to_three(): void
    {
        // Workspace milik freelancer lain tidak boleh muncul.
        $this->makeWorkspace($this->otherFreelancer, 'Punya Freelancer Lain', 'Sedang Dikerjakan');

        // Workspace 'Selesai' bukan pekerjaan aktif -> tidak ditampilkan.
        $this->makeWorkspace($this->freelancer, 'Proyek Sudah Selesai', 'Selesai');

        // 4 workspace aktif milik freelancer yang login -> maksimal 3 kartu.
        foreach (range(1, 4) as $i) {
            $this->makeWorkspace($this->freelancer, 'Pekerjaan Aktif ' . $i, 'Sedang Dikerjakan');
        }

        $response = $this->actingAs($this->freelancer)->get('/freelancer/dashboard');

        $response->assertOk();
        $response->assertDontSee('Punya Freelancer Lain');
        $response->assertDontSee('Proyek Sudah Selesai');

        $html = (string) $response->getContent();
        $this->assertSame(3, substr_count($html, 'Lihat Pekerjaan'));
    }
}
