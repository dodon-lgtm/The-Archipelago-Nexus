<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CompanyAccountRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Regression test untuk akun demo hasil `php artisan db:seed`.
 *
 * Konteks insiden: database `nexus` sudah dimigrasi (semua migration
 * berstatus "Ran") tetapi seeder belum pernah dijalankan sehingga tabel
 * `users` kosong. Akibatnya halaman login selalu menolak semua akun demo
 * dengan pesan "Email atau password salah." walau kodenya benar.
 *
 * Test ini mengunci kontrak berikut:
 * - DatabaseSeeder (AdminUserSeeder, UserRoleSeeder, CompanyProjectSeeder)
 *   menyediakan satu akun demo untuk tiap role.
 * - AuthController::login() mengarahkan tiap role ke dashboard-nya.
 * - Akun company tetap ditolak selama pengajuan belum disetujui admin.
 * - Data proyek demo untuk pengujian UI ikut terbuat.
 */
class SeededAccountLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Akun demo + password + role + dashboard tujuan.
     *
     * Sumber kebenaran credential ada di database/seeders:
     * AdminUserSeeder, UserRoleSeeder, dan CompanyProjectSeeder. Jika
     * salah satu password di seeder diubah, test ini harus ikut diubah
     * supaya akun uji tetap dapat dipakai tim.
     */
    private const DEMO_ACCOUNTS = [
        'admin' => [
            'email'     => 'admin@vexus.id',
            'password'  => 'admin123',
            'role'      => 'admin',
            'dashboard' => 'admin.dashboard',
        ],
        'company' => [
            'email'     => 'company@vexus.id',
            'password'  => 'company123',
            'role'      => 'company',
            'dashboard' => 'company.dashboard',
        ],
        'freelancer' => [
            'email'     => 'freelancer@vexus.id',
            'password'  => 'freelancer123',
            'role'      => 'freelancer',
            'dashboard' => 'freelancer.dashboard',
        ],
        'company_demo_projects' => [
            'email'     => 'nusantara@vexus.id',
            'password'  => 'company123',
            'role'      => 'company',
            'dashboard' => 'company.dashboard',
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        // Seeder lengkap, sama seperti menjalankan `php artisan db:seed`.
        $this->seed();
    }

    /** Seeder wajib membuat seluruh akun demo beserta role yang benar. */
    public function test_seeder_creates_demo_accounts_with_expected_roles(): void
    {
        foreach (self::DEMO_ACCOUNTS as $label => $account) {
            $user = User::query()->where('email', $account['email'])->first();

            $this->assertInstanceOf(
                User::class,
                $user,
                "Akun demo '{$label}' ({$account['email']}) tidak dibuat oleh seeder."
            );

            $this->assertSame(
                $account['role'],
                $user->role,
                "Role akun demo '{$label}' berbeda dari seeder."
            );

            $this->assertTrue(
                Hash::check($account['password'], (string) $user->password),
                "Password akun demo '{$label}' tidak cocok dengan seeder."
            );
        }
    }

    /**
     * Regression insiden: setiap akun demo harus bisa login dan langsung
     * diarahkan ke dashboard sesuai role-nya.
     */
    public function test_every_demo_account_can_login_and_open_its_dashboard(): void
    {
        foreach (self::DEMO_ACCOUNTS as $label => $account) {
            $user = User::query()->where('email', $account['email'])->firstOrFail();

            $this->post(route('login'), [
                'email'    => $account['email'],
                'password' => $account['password'],
            ])->assertRedirect(route($account['dashboard']));

            // Guard default (web) harus mengenali user hasil login ini.
            $this->assertAuthenticatedAs($user);

            // Halaman dashboard harus benar-benar bisa dibuka (bukan
            // dilempar balik ke halaman login atau error 500).
            $this->get(route($account['dashboard']))
                ->assertOk()
                ->assertSee('</html>', false);

            // Reset sesi agar iterasi berikutnya menguji login dari nol.
            Auth::logout();
            $this->flushSession();
        }
    }

    /** Password salah tetap ditolak (guard login tidak boleh longgar). */
    public function test_demo_account_login_rejected_with_wrong_password(): void
    {
        $response = $this
            ->from(route('login'))
            ->post(route('login'), [
                'email'    => self::DEMO_ACCOUNTS['admin']['email'],
                'password' => 'password-salah',
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** Akun company demo tetap ditolak sebelum pengajuan disetujui admin. */
    public function test_demo_company_login_blocked_when_request_not_approved(): void
    {
        $email = self::DEMO_ACCOUNTS['company']['email'];

        CompanyAccountRequest::query()
            ->where('company_email', $email)
            ->update(['request_status' => 'menunggu']);

        $response = $this
            ->from(route('login'))
            ->post(route('login'), [
                'email'    => $email,
                'password' => self::DEMO_ACCOUNTS['company']['password'],
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** Data pendukung pengujian UI (kategori + proyek demo) ikut terbuat. */
    public function test_seeder_creates_demo_project_data(): void
    {
        $company = User::query()
            ->where('email', self::DEMO_ACCOUNTS['company_demo_projects']['email'])
            ->firstOrFail();

        $this->assertGreaterThan(0, Category::query()->count());

        $project = Project::query()->where('user_id', $company->id)->first();

        $this->assertInstanceOf(
            Project::class,
            $project,
            'Seeder tidak membuat proyek demo untuk akun company.'
        );

        $this->assertSame(Project::STATUS_OPEN, $project->status);
        $this->assertNotEmpty($project->stages, 'Proyek demo harus punya tahapan (stages).');
    }
}
