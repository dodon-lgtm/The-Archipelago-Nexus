<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\Penawaran;
use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Regression test halaman Company dengan search/filter:
 * - /company/projects (search + status) → kontrak live filter + query filter
 * - /company/workspaces (search + project + status) → kontainer live fetch
 * - /company/projects/{project} (sort) → sort client-side live tanpa reload
 */
class CompanyProjectLiveFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $company;
    private Project $laravelProject;
    private Project $designProject;
    private Project $closedProject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = User::factory()->create(['role' => 'company']);

        // Middleware ensureCompanyAdminOrAbort mensyaratkan request akun
        // perusahaan yang sudah disetujui admin.
        CompanyAccountRequest::create([
            'company_name'    => $this->company->name,
            'contact_person'  => $this->company->name,
            'company_email'   => $this->company->email,
            'company_phone'   => '081234567890',
            'company_address' => 'Alamat Perusahaan',
            'request_status'  => 'disetujui',
        ]);

        $this->laravelProject = Project::factory()->create([
            'user_id'      => $this->company->id,
            'project_name' => 'Laravel API Platform',
            'status'       => Project::STATUS_OPEN,
        ]);

        $this->designProject = Project::factory()->create([
            'user_id'      => $this->company->id,
            'project_name' => 'Desain Logo Brand',
            'status'       => Project::STATUS_OPEN,
        ]);

        $this->closedProject = Project::factory()->create([
            'user_id'      => $this->company->id,
            'project_name' => 'Laravel Maintenance Tertutup',
            'status'       => Project::STATUS_CLOSED,
        ]);
    }

    private function projectsIndex(array $query = []): TestResponse
    {
        return $this->actingAs($this->company)->get(route('company.projects.index', $query));
    }

    private function workspacesIndex(array $query = []): TestResponse
    {
        return $this->actingAs($this->company)->get(route('company.workspaces.index', $query));
    }

    private function createWorkspace(Project $project, string $status): Workspace
    {
        $freelancer = User::factory()->create(['role' => 'freelancer']);

        return Workspace::create([
            'project_id'    => $project->id,
            'company_id'    => $this->company->id,
            'freelancer_id' => $freelancer->id,
            'status'        => $status,
        ]);
    }

    /**
     * Nama project juga muncul di JSON saran pencarian (dropdown) sehingga
     * assertDontSee pada seluruh halaman selalu gagal. Assert-nya difokuskan
     * ke grid hasil lewat atribut data-project-name pada kartu .ws-card.
     */
    private function assertGridHasProject(TestResponse $response, string $projectName): void
    {
        // View menyimpan data-project-name dalam huruf kecil (strtolower).
        $this->assertStringContainsString(
            'data-project-name="' . strtolower($projectName) . '"',
            $response->getContent()
        );
    }

    private function assertGridMissingProject(TestResponse $response, string $projectName): void
    {
        $this->assertStringNotContainsString(
            'data-project-name="' . strtolower($projectName) . '"',
            $response->getContent()
        );
    }

    // ─── KONTRAK LIVE FILTER: /company/projects ─────────────────────

    public function test_projects_index_has_live_filter_contract(): void
    {
        $response = $this->projectsIndex();
        $response->assertOk();
        $response->assertSee('data-live-filter', false);
        $response->assertSee('id="project-results"', false);
        $response->assertSee('data-live-filter-reset', false);
        $response->assertSee('company-live-filter.js', false);
    }

    public function test_projects_index_has_no_manual_apply_button(): void
    {
        // Tombol "Terapkan" sudah dihapus — filter berjalan otomatis saat mengetik/memilih.
        $this->projectsIndex()->assertDontSee('Terapkan', false);
    }

    // ─── QUERY FILTER: /company/projects ────────────────────────────

    public function test_search_filters_projects(): void
    {
        $response = $this->projectsIndex(['search' => 'Laravel']);
        $response->assertOk();
        $response->assertSee('Laravel API Platform');
        $response->assertDontSee('Desain Logo Brand');
    }

    public function test_empty_search_returns_all_projects(): void
    {
        $response = $this->projectsIndex(['search' => '']);
        $response->assertOk();
        $response->assertSee('Laravel API Platform');
        $response->assertSee('Desain Logo Brand');
        $response->assertSee('Laravel Maintenance Tertutup');
    }

    public function test_status_filter_open_shows_only_open(): void
    {
        $response = $this->projectsIndex(['status' => Project::STATUS_OPEN]);
        $response->assertOk();
        $response->assertSee('Laravel API Platform');
        $response->assertSee('Desain Logo Brand');
        $response->assertDontSee('Laravel Maintenance Tertutup');
    }

    public function test_status_filter_closed_shows_only_closed(): void
    {
        $response = $this->projectsIndex(['status' => Project::STATUS_CLOSED]);
        $response->assertOk();
        $response->assertSee('Laravel Maintenance Tertutup');
        $response->assertDontSee('Desain Logo Brand');
    }

    public function test_search_and_status_combine(): void
    {
        $response = $this->projectsIndex([
            'search' => 'Laravel',
            'status' => Project::STATUS_CLOSED,
        ]);
        $response->assertOk();
        $response->assertSee('Laravel Maintenance Tertutup');
        $response->assertDontSee('Laravel API Platform');
        $response->assertDontSee('Desain Logo Brand');
    }

    public function test_unknown_status_is_ignored(): void
    {
        $response = $this->projectsIndex(['status' => 'bukan-status-valid']);
        $response->assertOk();
        $response->assertSee('Laravel API Platform');
        $response->assertSee('Laravel Maintenance Tertutup');
    }

    public function test_empty_state_when_filter_matches_nothing(): void
    {
        $response = $this->projectsIndex(['search' => 'katakuncitidakada']);
        $response->assertOk();
        $response->assertSee('Tidak Ada Proyek Sesuai Filter');
        $response->assertDontSee('Laravel API Platform');
    }

    public function test_reset_returns_all_projects(): void
    {
        $filtered = $this->projectsIndex(['search' => 'Laravel']);
        $filtered->assertDontSee('Desain Logo Brand');

        $reset = $this->projectsIndex();
        $reset->assertOk();
        $reset->assertSee('Laravel API Platform');
        $reset->assertSee('Desain Logo Brand');
    }

    public function test_only_own_projects_are_listed(): void
    {
        $other = User::factory()->create(['role' => 'company']);
        Project::factory()->create([
            'user_id'      => $other->id,
            'project_name' => 'Proyek Company Lain',
            'status'       => Project::STATUS_OPEN,
        ]);

        $this->projectsIndex()->assertDontSee('Proyek Company Lain');
    }

    // ─── /company/workspaces (search + project + status) ────────────

    public function test_workspaces_page_has_live_filter_container(): void
    {
        $response = $this->workspacesIndex();
        $response->assertOk();
        $response->assertSee('id="workspace-results"', false);
    }

    public function test_workspaces_search_filters_by_project_name(): void
    {
        $this->createWorkspace($this->laravelProject, 'Sedang Dikerjakan');
        $this->createWorkspace($this->designProject, 'Selesai');

        $response = $this->workspacesIndex(['search' => 'Laravel']);
        $response->assertOk();
        $this->assertGridHasProject($response, 'Laravel API Platform');
        $this->assertGridMissingProject($response, 'Desain Logo Brand');
    }

    public function test_workspaces_status_filter(): void
    {
        $this->createWorkspace($this->laravelProject, 'Sedang Dikerjakan');
        $this->createWorkspace($this->designProject, 'Selesai');

        $response = $this->workspacesIndex(['status' => 'Selesai']);
        $response->assertOk();
        $this->assertGridHasProject($response, 'Desain Logo Brand');
        $this->assertGridMissingProject($response, 'Laravel API Platform');
    }

    public function test_workspaces_project_filter(): void
    {
        $this->createWorkspace($this->laravelProject, 'Sedang Dikerjakan');
        $this->createWorkspace($this->designProject, 'Selesai');

        $response = $this->workspacesIndex(['project' => $this->laravelProject->id]);
        $response->assertOk();
        $this->assertGridHasProject($response, 'Laravel API Platform');
        $this->assertGridMissingProject($response, 'Desain Logo Brand');
    }

    public function test_workspaces_combined_filters(): void
    {
        $this->createWorkspace($this->laravelProject, 'Sedang Dikerjakan');
        $this->createWorkspace($this->designProject, 'Selesai');

        $response = $this->workspacesIndex([
            'search'  => 'Laravel',
            'status'  => 'Sedang Dikerjakan',
            'project' => $this->laravelProject->id,
        ]);
        $response->assertOk();
        $this->assertGridHasProject($response, 'Laravel API Platform');
        $this->assertGridMissingProject($response, 'Desain Logo Brand');
    }

    public function test_workspaces_empty_state_when_filter_matches_nothing(): void
    {
        $this->createWorkspace($this->laravelProject, 'Sedang Dikerjakan');

        $response = $this->workspacesIndex(['search' => 'katakuncitidakada']);
        $response->assertOk();
        $response->assertSee('Tidak ada workspace yang sesuai filter');
        $this->assertGridMissingProject($response, 'Laravel API Platform');
    }

    public function test_workspaces_fully_empty_state(): void
    {
        $response = $this->workspacesIndex();
        $response->assertOk();
        $response->assertSee('Belum Ada Workspace');
    }

    // ─── Halaman detail project (sort live) ─────────────────────────

    public function test_project_show_has_live_sort_contract(): void
    {
        // Link sort dirender hanya jika project memiliki penawaran.
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        Penawaran::create([
            'project_id'     => $this->laravelProject->id,
            'freelancer_id'  => $freelancer->id,
            'harga_penawaran'=> 1500000,
            'estimasi_hari'  => 10,
            'pesan'          => 'Saya siap mengerjakan proyek ini.',
            'status'         => 'Menunggu',
        ]);

        $response = $this->actingAs($this->company)
            ->get(route('company.projects.show', $this->laravelProject));

        $response->assertOk();
        $response->assertSee('sortOption', false);
        $response->assertSee("setSort('')", false);
        $response->assertSee("setSort('harga_tertinggi')", false);
        $response->assertSee("setSort('harga_terendah')", false);
    }

    public function test_project_show_sort_query_does_not_break(): void
    {
        foreach (['', 'harga_tertinggi', 'harga_terendah', 'sort-asing'] as $sort) {
            $query = $sort === '' ? [] : ['sort' => $sort];
            $this->actingAs($this->company)
                ->get(route('company.projects.show', array_merge(['project' => $this->laravelProject], $query)))
                ->assertOk();
        }
    }

    // ─── Pagination & keamanan ──────────────────────────────────────

    public function test_pagination_page_parameter_keeps_filters_working(): void
    {
        foreach (range(1, 12) as $i) {
            Project::factory()->create([
                'user_id'      => $this->company->id,
                'project_name' => "Proyek Nomor {$i}",
                'status'       => Project::STATUS_OPEN,
            ]);
        }

        $this->projectsIndex(['page' => 2])->assertOk();
        $this->projectsIndex(['search' => 'Laravel', 'page' => 1])->assertOk();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/company/projects')->assertRedirect(route('login'));
        $this->get('/company/workspaces')->assertRedirect(route('login'));
    }
}
