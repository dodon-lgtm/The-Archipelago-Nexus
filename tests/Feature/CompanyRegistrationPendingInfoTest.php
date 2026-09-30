<?php

namespace Tests\Feature;

use App\Http\Requests\HelpContactRequest;
use App\Models\CompanyAccountRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature test untuk informasi "Pendaftaran Akun Perusahaan Sedang Diproses"
 * pada halaman login.
 *
 * Mekanisme yang diuji:
 * - RegisterController menyimpan identifier `pending_company_email` di session.
 * - AuthController::showLogin() memverifikasi status ke tabel
 *   `company_account_requests` (request_status = menunggu).
 * - Kartu pending + tombol "Hubungi Admin" hanya muncul selama status menunggu.
 */
class CompanyRegistrationPendingInfoTest extends TestCase
{
    use RefreshDatabase;

    private const COMPANY_EMAIL = 'company@example.com';
    private const FREELANCER_EMAIL = 'freelancer@example.com';

    protected function setUp(): void
    {
        parent::setUp();

        // Policy wajib agar consent system existing tetap berjalan.
        $this->seed(\Database\Seeders\PolicySeeder::class);
    }

    private function companyRegistrationPayload(array $overrides = []): array
    {
        return array_merge([
            'name'                  => 'Budi Santoso',
            'email'                 => self::COMPANY_EMAIL,
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'is_company'            => 1,
            'company_name'          => 'PT Contoh Jaya',
            'company_phone'         => '081234567890',
            'company_address'       => 'Jl. Contoh No. 1, Jakarta',
            'company_description'   => 'Perusahaan pengujian.',
            'terms_accepted'        => '1',
            'privacy_accepted'      => '1',
            'usage_accepted'        => '1',
        ], $overrides);
    }

    private function freelancerRegistrationPayload(array $overrides = []): array
    {
        return array_merge([
            'name'                  => 'Siti Aminah',
            'email'                 => self::FREELANCER_EMAIL,
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'phone'                 => '081298765432',
            'is_company'            => 0,
            'terms_accepted'        => '1',
            'privacy_accepted'      => '1',
            'usage_accepted'        => '1',
        ], $overrides);
    }

    /** TEST 1 */
    public function test_company_registration_stores_pending_identifier_and_redirects_to_login(): void
    {
        $response = $this->post(route('register'), $this->companyRegistrationPayload());

        // Redirect + flash success existing tetap dipertahankan.
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('pending_company_email', self::COMPANY_EMAIL);

        // User dibuat dengan role company.
        $this->assertDatabaseHas('users', [
            'email' => self::COMPANY_EMAIL,
            'role'  => 'company',
        ]);

        // CompanyAccountRequest dibuat dengan status menunggu.
        $this->assertDatabaseHas('company_account_requests', [
            'company_email'  => self::COMPANY_EMAIL,
            'request_status' => 'menunggu',
        ]);

        // CompanyProfile otomatis dibuat oleh workflow existing.
        $user = User::query()->where('email', self::COMPANY_EMAIL)->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->companyProfile);

        // Consent system existing tidak berubah: 3 policy wajib tersimpan.
        $this->assertSame(3, $user->consents()->count());

        // Session hanya menyimpan identifier, bukan password / object request.
        $this->assertArrayNotHasKey('password', session()->all());
        $this->assertArrayNotHasKey('pending_company_request', session()->all());
    }

    /** TEST 2 */
    public function test_login_page_shows_pending_card_with_help_center_email(): void
    {
        $this->post(route('register'), $this->companyRegistrationPayload());

        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('Pendaftaran Akun Perusahaan Sedang Diproses');
        $response->assertSee('Hubungi Admin');

        // Alamat email tombol "Hubungi Admin" = sumber yang sama dengan Pusat Bantuan.
        $response->assertSee('mailto:' . config('mail.help_to'), false);

        // Form login existing tetap ada.
        $response->assertSee('Masuk');

        // Data pending request dikirim ke view.
        $pending = $response->viewData('pendingCompanyRequest');
        $this->assertNotNull($pending);
        $this->assertSame(self::COMPANY_EMAIL, $pending->company_email);
        $this->assertSame('menunggu', $pending->request_status);

        // Tidak ada endpoint/query parameter publik untuk cek status.
        $response->assertDontSee('?email=', false);

        // ---- Verifikasi format email "Hubungi Admin" (mailto ter-encode) ----
        $html = $response->getContent();

        $this->assertMatchesRegularExpression('/href="mailto:[^"]+"/', $html);

        preg_match('/href="(mailto:[^"]+)"/', $html, $matches);
        $mailtoUrl = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        $decodedMailto = rawurldecode($mailtoUrl);

        // Alamat tujuan = single source of truth Pusat Bantuan.
        $this->assertStringStartsWith('mailto:' . config('mail.help_to') . '?', $mailtoUrl);

        // Subjek memakai pola Pusat Bantuan + kategori akun existing.
        $this->assertStringContainsString(
            '[Pusat Bantuan Vexus][' . HelpContactRequest::categoryLabel(HelpContactRequest::CATEGORY_AKUN) . '] Verifikasi Pendaftaran Akun Perusahaan',
            $decodedMailto
        );

        // Body memuat informasi user yang tersedia + tidak memuat password.
        $this->assertStringContainsString('Budi Santoso', $decodedMailto);
        $this->assertStringContainsString('PT Contoh Jaya', $decodedMailto);
        $this->assertStringContainsString(self::COMPANY_EMAIL, $decodedMailto);
        $this->assertStringContainsString('Company', $decodedMailto);
        $this->assertStringNotContainsString('password123', $decodedMailto);
    }

    /** TEST 3 */
    public function test_pending_card_disappears_after_request_approved(): void
    {
        $this->post(route('register'), $this->companyRegistrationPayload());

        CompanyAccountRequest::query()
            ->where('company_email', self::COMPANY_EMAIL)
            ->update(['request_status' => 'disetujui']);

        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertDontSee('Pendaftaran Akun Perusahaan Sedang Diproses');
        $response->assertDontSee('Hubungi Admin');
        $response->assertViewHas('pendingCompanyRequest', null);

        // Identifier session dibersihkan karena sudah tidak ada request menunggu.
        $response->assertSessionMissing('pending_company_email');
    }

    /** TEST 4 */
    public function test_pending_card_disappears_after_request_rejected(): void
    {
        $this->post(route('register'), $this->companyRegistrationPayload());

        CompanyAccountRequest::query()
            ->where('company_email', self::COMPANY_EMAIL)
            ->update(['request_status' => 'ditolak']);

        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertDontSee('Pendaftaran Akun Perusahaan Sedang Diproses');
        $response->assertDontSee('Hubungi Admin');
        $response->assertViewHas('pendingCompanyRequest', null);
        $response->assertSessionMissing('pending_company_email');
    }

    /** TEST 5 */
    public function test_freelancer_registration_is_not_affected(): void
    {
        $response = $this->post(route('register'), $this->freelancerRegistrationPayload());

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $response->assertSessionMissing('pending_company_email');

        $this->assertDatabaseHas('users', [
            'email' => self::FREELANCER_EMAIL,
            'role'  => 'freelancer',
        ]);
        $this->assertDatabaseMissing('company_account_requests', [
            'company_email' => self::FREELANCER_EMAIL,
        ]);

        $login = $this->get(route('login'));
        $login->assertOk();
        $login->assertDontSee('Pendaftaran Akun Perusahaan Sedang Diproses');
        $login->assertViewHas('pendingCompanyRequest', null);
    }

    /** TEST 6 */
    public function test_login_page_without_pending_session_renders_normally(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertDontSee('Pendaftaran Akun Perusahaan Sedang Diproses');
        $response->assertDontSee('Hubungi Admin');
        $response->assertViewHas('pendingCompanyRequest', null);
        $response->assertSessionMissing('pending_company_email');

        // Elemen login normal tetap tersedia.
        $response->assertSee('Masuk');
        $response->assertSee('Daftar di sini');
        $response->assertSee('Lupa?');
    }

    /** TEST 7 */
    public function test_pending_card_persists_across_login_page_refresh(): void
    {
        $this->post(route('register'), $this->companyRegistrationPayload());

        // Buka halaman login tiga kali selama status masih "menunggu".
        for ($i = 0; $i < 3; $i++) {
            $this->get(route('login'))
                ->assertOk()
                ->assertSee('Pendaftaran Akun Perusahaan Sedang Diproses')
                ->assertSee('Hubungi Admin');
        }

        // Identifier persist di session (bukan flash sekali tampil).
        $this->assertSame(self::COMPANY_EMAIL, session('pending_company_email'));
    }

    /** Regression: company tetap tidak bisa login sebelum disetujui admin. */
    public function test_company_login_still_blocked_before_admin_approval(): void
    {
        $this->post(route('register'), $this->companyRegistrationPayload());

        $response = $this
            ->from(route('login'))
            ->post(route('login'), [
                'email'    => self::COMPANY_EMAIL,
                'password' => 'password123',
            ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** Regression: setelah disetujui, login normal company berjalan. */
    public function test_company_can_login_after_admin_approval(): void
    {
        $this->post(route('register'), $this->companyRegistrationPayload());

        CompanyAccountRequest::query()
            ->where('company_email', self::COMPANY_EMAIL)
            ->update(['request_status' => 'disetujui']);

        $response = $this->post(route('login'), [
            'email'    => self::COMPANY_EMAIL,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('company.dashboard'));
        $this->assertAuthenticated();
    }
}
