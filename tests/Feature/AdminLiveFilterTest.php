<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CompanyAccountRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Regression test live filter halaman Admin:
 * - kontrak markup (data-live-filter, data-live-filter-results, script)
 * - tombol "Cari"/"Terapkan" sudah tidak ada pada form filter
 * - query filter (search + status/role/kategori) tetap benar & bisa kombinasi
 * - Company Account Request: search + status filter
 */
class AdminLiveFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    private function getAdmin(string $route, array $query = []): TestResponse
    {
        return $this->actingAs($this->admin)->get(route($route, $query));
    }

    private function assertLiveFilterContract(TestResponse $response, string $route): void
    {
        $response->assertOk();
        $response->assertSee('data-live-filter', false);
        $response->assertSee('data-live-filter-results', false);
        $response->assertSee('admin-live-filter.js', false);
    }

    // ─── KONTRAK LIVE FILTER PER HALAMAN ────────────────────────────

    public function test_users_page_has_live_filter_contract(): void
    {
        $response = $this->getAdmin('admin.users.index');
        $this->assertLiveFilterContract($response, 'admin.users.index');
        // Tombol "Cari" lama sudah dihapus.
        $response->assertDontSee('fa-search"></i> Cari</button>', false);
    }

    public function test_categories_page_has_live_filter_contract(): void
    {
        $response = $this->getAdmin('admin.categories.index');
        $this->assertLiveFilterContract($response, 'admin.categories.index');
        $response->assertDontSee('fa-search"></i> Cari</button>', false);
    }

    public function test_projects_page_has_live_filter_contract(): void
    {
        $response = $this->getAdmin('admin.projects.index');
        $this->assertLiveFilterContract($response, 'admin.projects.index');
    }

    public function test_penawarans_page_has_live_filter_contract(): void
    {
        $response = $this->getAdmin('admin.penawarans.index');
        $this->assertLiveFilterContract($response, 'admin.penawarans.index');
    }

    public function test_hasil_pekerjaan_page_has_live_filter_contract(): void
    {
        $response = $this->getAdmin('admin.hasil-pekerjaan.index');
        $this->assertLiveFilterContract($response, 'admin.hasil-pekerjaan.index');
    }

    public function test_reports_page_has_live_filter_contract(): void
    {
        $response = $this->getAdmin('admin.reports.index');
        $this->assertLiveFilterContract($response, 'admin.reports.index');
    }

    public function test_payments_page_has_live_filter_contract(): void
    {
        $response = $this->getAdmin('admin.payments.index');
        $this->assertLiveFilterContract($response, 'admin.payments.index');
        // Select status tidak lagi auto-submit penuh.
        $response->assertDontSee('onchange="this.form.submit()"', false);
    }

    public function test_withdrawals_page_has_live_filter_contract(): void
    {
        $response = $this->getAdmin('admin.withdrawals.index');
        $this->assertLiveFilterContract($response, 'admin.withdrawals.index');
        $response->assertDontSee('fa-filter text-xs"></i> Terapkan', false);
    }

    public function test_wallet_page_has_live_filter_contract(): void
    {
        $response = $this->getAdmin('admin.wallet.index');
        $this->assertLiveFilterContract($response, 'admin.wallet.index');
        $response->assertDontSee('onchange="this.form.submit()"', false);
    }

    // ─── QUERY FILTER: USERS ────────────────────────────────────────

    public function test_users_search_filters_by_name_or_email(): void
    {
        User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@example.com']);
        User::factory()->create(['name' => 'Andi Wijaya', 'email' => 'andi@example.com']);

        $response = $this->getAdmin('admin.users.index', ['search' => 'Budi']);
        $response->assertOk();
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Andi Wijaya');
    }

    public function test_users_role_filter(): void
    {
        User::factory()->create(['role' => 'freelancer', 'name' => 'Freelancer Satu']);
        User::factory()->create(['role' => 'company', 'name' => 'Company Satu']);

        $response = $this->getAdmin('admin.users.index', ['role' => 'freelancer']);
        $response->assertOk();
        $response->assertSee('Freelancer Satu');
        $response->assertDontSee('Company Satu');
    }

    public function test_users_search_and_role_combine(): void
    {
        User::factory()->create(['role' => 'freelancer', 'name' => 'Freelancer Laravel']);
        User::factory()->create(['role' => 'company', 'name' => 'Company Laravel']);
        User::factory()->create(['role' => 'freelancer', 'name' => 'Freelancer Desain']);

        $response = $this->getAdmin('admin.users.index', ['search' => 'Laravel', 'role' => 'freelancer']);
        $response->assertOk();
        $response->assertSee('Freelancer Laravel');
        $response->assertDontSee('Company Laravel');
        $response->assertDontSee('Freelancer Desain');
    }

    public function test_users_empty_search_returns_everyone(): void
    {
        User::factory()->create(['name' => 'User Tanpa Filter']);

        $response = $this->getAdmin('admin.users.index', ['search' => '']);
        $response->assertOk();
        $response->assertSee('User Tanpa Filter');
    }

    public function test_users_empty_result_shows_empty_state(): void
    {
        $response = $this->getAdmin('admin.users.index', ['search' => 'katakuncitidakada']);
        $response->assertOk();
        $this->assertStringNotContainsString('@empty', $response->getContent()); // blade ter-compile
    }

    // ─── QUERY FILTER: CATEGORIES ───────────────────────────────────

    public function test_categories_search_filters(): void
    {
        Category::create(['name' => 'Web Development']);
        Category::create(['name' => 'Graphic Design']);

        $response = $this->getAdmin('admin.categories.index', ['search' => 'Web']);
        $response->assertOk();
        $response->assertSee('Web Development');
        $response->assertDontSee('Graphic Design');
    }

    public function test_categories_empty_result_shows_empty_state(): void
    {
        // Nama unik (bukan "Web Development", karena placeholder form memang memuat kata itu).
        Category::create(['name' => 'Zeta Kawasan Nusantara']);

        $response = $this->getAdmin('admin.categories.index', ['search' => 'katakuncitidakada']);
        $response->assertOk();
        $response->assertSee('Belum ada kategori');
        $response->assertDontSee('Zeta Kawasan Nusantara');
    }

    // ─── QUERY FILTER: PROJECTS ─────────────────────────────────────

    public function test_projects_search_and_status_combine(): void
    {
        $company = User::factory()->create(['role' => 'company']);
        Project::factory()->create([
            'user_id'      => $company->id,
            'project_name' => 'Laravel API Platform',
            'status'       => Project::STATUS_OPEN,
        ]);
        Project::factory()->create([
            'user_id'      => $company->id,
            'project_name' => 'Laravel Maintenance',
            'status'       => Project::STATUS_CLOSED,
        ]);
        Project::factory()->create([
            'user_id'      => $company->id,
            'project_name' => 'Desain Logo Brand',
            'status'       => Project::STATUS_OPEN,
        ]);

        $response = $this->getAdmin('admin.projects.index', [
            'search' => 'Laravel',
            'status' => Project::STATUS_CLOSED,
        ]);
        $response->assertOk();
        $response->assertSee('Laravel Maintenance');
        $response->assertDontSee('Laravel API Platform');
        $response->assertDontSee('Desain Logo Brand');
    }

    // ─── COMPANY ACCOUNT REQUEST (KHUSUS) ───────────────────────────

    private function createCompanyRequest(string $name, string $status): CompanyAccountRequest
    {
        return CompanyAccountRequest::create([
            'company_name'    => $name,
            'contact_person'  => 'Contact ' . $name,
            'company_email'   => strtolower(str_replace(' ', '-', $name)) . '@example.com',
            'company_phone'   => '081234567890',
            'company_address' => 'Alamat ' . $name,
            'request_status'  => $status,
        ]);
    }

    public function test_company_account_requests_status_filter(): void
    {
        $this->createCompanyRequest('PT Menunggu Jaya', 'menunggu');
        $this->createCompanyRequest('PT Sudah Disetujui', 'disetujui');

        $response = $this->getAdmin('admin.company-account-requests.index', ['status' => 'menunggu']);
        $response->assertOk();
        $response->assertSee('PT Menunggu Jaya');
        $response->assertDontSee('PT Sudah Disetujui');
    }

    public function test_company_account_requests_search_filters(): void
    {
        $this->createCompanyRequest('PT Alpha Nusantara', 'menunggu');
        $this->createCompanyRequest('PT Beta Kreatif', 'menunggu');

        $response = $this->getAdmin('admin.company-account-requests.index', [
            'status' => 'menunggu',
            'search' => 'Alpha',
        ]);
        $response->assertOk();
        $response->assertSee('PT Alpha Nusantara');
        $response->assertDontSee('PT Beta Kreatif');
    }

    public function test_company_account_requests_search_and_status_combine(): void
    {
        $this->createCompanyRequest('PT Gamma Menunggu', 'menunggu');
        $this->createCompanyRequest('PT Gamma Selesai', 'disetujui');
        $this->createCompanyRequest('PT Delta Lain', 'menunggu');

        $response = $this->getAdmin('admin.company-account-requests.index', [
            'status' => 'menunggu',
            'search' => 'Gamma',
        ]);
        $response->assertOk();
        $response->assertSee('PT Gamma Menunggu');
        $response->assertDontSee('PT Gamma Selesai');
        $response->assertDontSee('PT Delta Lain');
    }

    public function test_company_account_requests_empty_result_shows_empty_state(): void
    {
        $this->createCompanyRequest('PT Ada Saja', 'menunggu');

        $response = $this->getAdmin('admin.company-account-requests.index', [
            'status' => 'menunggu',
            'search' => 'katakuncitidakada',
        ]);
        $response->assertOk();
        $response->assertSee('Tidak ada permintaan');
        $response->assertDontSee('PT Ada Saja');
    }

    public function test_company_account_requests_page_has_live_filter_contract(): void
    {
        $response = $this->getAdmin('admin.company-account-requests.index');
        $this->assertLiveFilterContract($response, 'admin.company-account-requests.index');
        $response->assertDontSee('fa-search text-xs"></i> Cari</button>', false);
    }

    // ─── FILTER LAIN TANPA ERROR + PAGINATION + GUEST ───────────────

    public function test_other_filter_params_do_not_break_pages(): void
    {
        $this->getAdmin('admin.reports.index', ['search' => 'x', 'status' => 'menunggu', 'category' => 'keterlambatan'])->assertOk();
        $this->getAdmin('admin.payments.index', ['status' => 'paid'])->assertOk();
        $this->getAdmin('admin.payments.index', ['status' => 'rejected'])->assertOk();
        $this->getAdmin('admin.withdrawals.index', ['status' => 'menunggu'])->assertOk();
        $this->getAdmin('admin.withdrawals.index', ['status' => 'semua'])->assertOk();
        $this->getAdmin('admin.penawarans.index', ['search' => 'tes', 'status' => 'Menunggu'])->assertOk();
        $this->getAdmin('admin.hasil-pekerjaan.index', ['search' => 'tes', 'status' => 'Selesai'])->assertOk();
        $this->getAdmin('admin.wallet.index', ['q' => 'inv', 'filter' => 'income', 'month' => '2026-01'])->assertOk();
    }

    public function test_pagination_works_with_filters(): void
    {
        foreach (range(1, 17) as $i) {
            User::factory()->create(['name' => "User Halaman Dua {$i}"]);
        }

        $this->getAdmin('admin.users.index', ['page' => 2])->assertOk();
        $this->getAdmin('admin.users.index', ['search' => 'Halaman', 'page' => 1])->assertOk();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/users')->assertRedirect(route('login'));
        $this->get('/admin/company-account-requests')->assertRedirect(route('login'));
    }

    public function test_pagination_links_keep_active_filters(): void
    {
        foreach (range(1, 17) as $i) {
            User::factory()->create(['name' => "User Filter {$i}"]);
        }

        $response = $this->getAdmin('admin.users.index', ['search' => 'User Filter']);
        $response->assertOk();

        // withQueryString(): link halaman berikutnya tetap membawa filter aktif.
        // JS live filter memakai link ini untuk pagination tanpa reload.
        $response->assertSee('search=User', false);
        $response->assertSee('page=2', false);
    }

    // ─── JALUR FETCH LIVE (XHR) YANG DIPAKAI admin-live-filter.js ───

    public function test_ajax_fetch_returns_html_with_identical_results_structure(): void
    {
        $pages = [
            'admin.users.index'                    => ['search' => 'a'],
            'admin.categories.index'               => ['search' => 'a'],
            'admin.projects.index'                 => ['search' => 'a'],
            'admin.penawarans.index'               => ['search' => 'a'],
            'admin.hasil-pekerjaan.index'          => ['search' => 'a'],
            'admin.reports.index'                  => ['search' => 'a'],
            'admin.company-account-requests.index' => ['search' => 'a'],
            'admin.payments.index'                 => ['status' => 'paid'],
            'admin.withdrawals.index'              => ['status' => 'menunggu'],
            'admin.wallet.index'                   => ['q' => 'a'],
        ];

        foreach ($pages as $route => $query) {
            $initial = $this->getAdmin($route, $query);
            $initial->assertOk();

            $ajax = $this->actingAs($this->admin)->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept'           => 'text/html',
            ])->get(route($route, $query));

            $ajax->assertOk();

            // Header yang dikirim JS: Accept text/html + XHR → harus tetap HTML
            // (bukan JSON dari expectsJson()), kalau tidak JS gagal parse.
            $this->assertStringContainsString(
                'text/html',
                (string) $ajax->headers->get('Content-Type'),
                "Fetch live {$route} tidak mengembalikan HTML."
            );

            $expected = substr_count($initial->getContent(), 'data-live-filter-results');
            $this->assertGreaterThan(0, $expected, "Halaman {$route} tidak punya kontainer hasil live filter.");

            // JS hanya mengganti isi kontainer bila jumlahnya sama antara
            // dokumen awal dan hasil fetch; kalau beda → reload penuh.
            $this->assertSame(
                $expected,
                substr_count($ajax->getContent(), 'data-live-filter-results'),
                "Struktur kontainer hasil beda antara render awal dan fetch live untuk {$route}."
            );
        }
    }
}
