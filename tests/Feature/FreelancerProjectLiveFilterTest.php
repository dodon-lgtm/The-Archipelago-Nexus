<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Regression test halaman browse proyek Freelancer
 * (/freelancer/proyek & /freelancer/projects):
 * - query filter (search, kategori, budget, sort) tetap benar & bisa kombinasi
 * - kontrak markup live filter (data-live-filter, #project-results, script)
 */
class FreelancerProjectLiveFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $freelancer;
    private Category $webCategory;
    private Category $designCategory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->freelancer = User::factory()->create(['role' => 'freelancer']);
        $owner = User::factory()->create(['role' => 'company']);

        $this->webCategory = Category::create(['name' => 'Web Development']);
        $this->designCategory = Category::create(['name' => 'Graphic Design']);

        Project::factory()->create([
            'user_id'      => $owner->id,
            'category_id'  => $this->webCategory->id,
            'project_name' => 'Laravel API Platform',
            'budget'       => 2500000,
            'status'       => Project::STATUS_OPEN,
        ]);

        Project::factory()->create([
            'user_id'      => $owner->id,
            'category_id'  => $this->designCategory->id,
            'project_name' => 'Company Profile Laravel',
            'budget'       => 500000,
            'status'       => Project::STATUS_OPEN,
        ]);

        Project::factory()->create([
            'user_id'      => $owner->id,
            'category_id'  => $this->designCategory->id,
            'project_name' => 'Desain Logo Brand',
            'budget'       => 10000000,
            'status'       => Project::STATUS_OPEN,
        ]);
    }

    private function proyek(array $query = []): TestResponse
    {
        return $this->actingAs($this->freelancer)->get(route('freelancer.proyek', $query));
    }

    private function projectsIndex(array $query = []): TestResponse
    {
        return $this->actingAs($this->freelancer)->get(route('freelancer.projects.index', $query));
    }

    private function assertLiveFilterContract(TestResponse $response): void
    {
        $response->assertOk();
        $response->assertSee('data-live-filter', false);
        $response->assertSee('id="project-results"', false);
        $response->assertSee('data-live-filter-reset', false);
        $response->assertSee('freelancer-live-filter.js', false);
    }

    public function test_proyek_page_has_live_filter_contract(): void
    {
        $this->assertLiveFilterContract($this->proyek());
    }

    public function test_projects_index_page_has_live_filter_contract(): void
    {
        $this->assertLiveFilterContract($this->projectsIndex());
    }

    public function test_proyek_search_filters_results(): void
    {
        $response = $this->proyek(['search' => 'Laravel']);
        $response->assertOk();
        $response->assertSee('Laravel API Platform');
        $response->assertSee('Company Profile Laravel');
        $response->assertDontSee('Desain Logo Brand');
    }

    public function test_proyek_combines_search_and_category(): void
    {
        $response = $this->proyek([
            'search'   => 'Laravel',
            'category' => $this->webCategory->id,
        ]);
        $response->assertOk();
        $response->assertSee('Laravel API Platform');
        $response->assertDontSee('Company Profile Laravel');
        $response->assertDontSee('Desain Logo Brand');
    }

    public function test_proyek_category_filter_alone(): void
    {
        $response = $this->proyek(['category' => $this->designCategory->id]);
        $response->assertOk();
        $response->assertSee('Company Profile Laravel');
        $response->assertSee('Desain Logo Brand');
        $response->assertDontSee('Laravel API Platform');
    }

    public function test_proyek_budget_preset_filter(): void
    {
        $response = $this->proyek(['budget' => 'under-1m']);
        $response->assertOk();
        $response->assertSee('Company Profile Laravel');
        $response->assertDontSee('Laravel API Platform');
        $response->assertDontSee('Desain Logo Brand');
    }

    public function test_proyek_custom_budget_filter(): void
    {
        $response = $this->proyek(['budget' => 'Rp 2.500.000']);
        $response->assertOk();
        $response->assertSee('Laravel API Platform');
        $response->assertSee('Desain Logo Brand');
        $response->assertDontSee('Company Profile Laravel');
    }

    public function test_proyek_empty_state_when_nothing_matches(): void
    {
        $response = $this->proyek(['search' => 'katakuncitidakada']);
        $response->assertOk();
        $response->assertSee('Tidak Ada Proyek yang Cocok');
        $response->assertDontSee('Laravel API Platform');
        $response->assertDontSee('Desain Logo Brand');
    }

    public function test_proyek_reset_returns_all_projects(): void
    {
        $filtered = $this->proyek(['search' => 'Laravel']);
        $filtered->assertDontSee('Desain Logo Brand');

        $reset = $this->proyek();
        $reset->assertOk();
        $reset->assertSee('Laravel API Platform');
        $reset->assertSee('Company Profile Laravel');
        $reset->assertSee('Desain Logo Brand');
    }

    public function test_proyek_sort_options_do_not_break_query(): void
    {
        foreach (['terbaru', 'deadline', 'budget-tinggi', 'budget-rendah'] as $sort) {
            $this->proyek(['sort' => $sort])->assertOk();
        }
    }

    public function test_projects_index_search_and_category_filter(): void
    {
        $response = $this->projectsIndex([
            'search'      => 'Laravel',
            'category_id' => $this->webCategory->id,
        ]);
        $response->assertOk();
        $response->assertSee('Laravel API Platform');
        $response->assertDontSee('Company Profile Laravel');
        $response->assertDontSee('Desain Logo Brand');
    }

    public function test_projects_index_search_only(): void
    {
        $response = $this->projectsIndex(['search' => 'Desain']);
        $response->assertOk();
        $response->assertSee('Desain Logo Brand');
        $response->assertDontSee('Laravel API Platform');
    }

    public function test_projects_index_empty_state(): void
    {
        $response = $this->projectsIndex(['search' => 'katakuncitidakada']);
        $response->assertOk();
        $response->assertSee('Tidak ada proyek ditemukan');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/freelancer/proyek')->assertRedirect(route('login'));
        $this->get('/freelancer/projects')->assertRedirect(route('login'));
    }
}

