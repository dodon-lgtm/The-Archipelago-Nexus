<?php

namespace Tests\Feature;

use App\Models\Policy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature test modal kebijakan (Syarat & Ketentuan / Privasi / Penggunaan)
 * pada halaman register.
 *
 * Modal hanya mengubah cara user MEMBACA policy. Sistem consent
 * (RegisterRequest, RegisterController::saveUserConsents, user_consents,
 * policy versioning) tidak diubah.
 */
class RegisterPolicyModalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PolicySeeder::class);
    }

    private function policy(string $key): Policy
    {
        return Policy::query()->where('key', $key)->firstOrFail();
    }

    public function test_register_page_renders_policy_modal_with_database_content_and_version(): void
    {
        $this->policy(Policy::KEY_TERMS)->update([
            'content' => "PARAGRAF-TERMS-DARI-DB\n\nBaris kedua terms.",
            'version' => '2.5',
        ]);
        $this->policy(Policy::KEY_PRIVACY)->update([
            'content' => 'PARAGRAF-PRIVACY-DARI-DB',
            'version' => '1.4',
        ]);
        $this->policy(Policy::KEY_USAGE)->update([
            'content' => 'PARAGRAF-USAGE-DARI-DB',
            'version' => '3.0',
        ]);

        $response = $this->get(route('register'));
        $response->assertOk();

        $html = $response->getContent();

        // Trigger modal (bukan link ke halaman policy terpisah) dan
        // wajib type="button" agar klik tidak mengirim form register.
        foreach (['terms', 'privacy', 'usage'] as $key) {
            $this->assertStringContainsString('data-policy-modal="' . $key . '"', $html);
            $this->assertMatchesRegularExpression(
                '/<button[^>]*type="button"[^>]*data-policy-modal="' . $key . '"/s',
                $html
            );
        }

        // Tidak ada lagi navigasi ke halaman kebijakan.
        $response->assertDontSee('href="' . route('syarat-ketentuan') . '"', false);
        $response->assertDontSee('href="' . route('kebijakan-privasi') . '"', false);
        $response->assertDontSee('href="' . route('kebijakan-penggunaan') . '"', false);

        // Kontainer modal + tombol penutup tersedia di halaman register.
        foreach (['policyModal', 'policyModalBackdrop', 'policyModalClose', 'policyModalFooterClose', 'policyModalBody'] as $id) {
            $this->assertStringContainsString('id="' . $id . '"', $html);
        }

        // Isi policy berasal dari database (bukan hardcode di view).
        $response->assertSee('PARAGRAF-TERMS-DARI-DB', false);
        $response->assertSee('Baris kedua terms.', false);
        $response->assertSee('PARAGRAF-PRIVACY-DARI-DB', false);
        $response->assertSee('PARAGRAF-USAGE-DARI-DB', false);

        // Versi berasal dari kolom Policy::version.
        $response->assertSee('Versi: v2.5', false);
        $response->assertSee('Versi: v1.4', false);
        $response->assertSee('Versi: v3.0', false);

        // Tanggal berlaku memakai field existing (updated_at) seperti halaman policy existing.
        $response->assertSee('Berlaku sejak:', false);

        // Label judul tiap policy tetap tampil sebagai trigger.
        $response->assertSee('Syarat & Ketentuan Vexus', false);
        $response->assertSee('Kebijakan Privasi Vexus', false);
        $response->assertSee('Kebijakan Penggunaan Platform Vexus', false);

        // Checkbox consent tetap ada dan tetap required.
        foreach (['terms_accepted', 'privacy_accepted', 'usage_accepted'] as $field) {
            $this->assertMatchesRegularExpression('/name="' . $field . '"[^>]*required/s', $html);
        }
    }

    public function test_admin_policy_update_is_reflected_on_register_modal(): void
    {
        $admin = User::create([
            'name'     => 'Admin Apex',
            'email'    => 'admin@example.com',
            'password' => 'password123',
            'role'     => 'admin',
        ]);

        $terms = $this->policy(Policy::KEY_TERMS);

        // Admin mengubah isi + versi policy lewat sistem Admin Policy existing.
        $this->actingAs($admin)
            ->put(route('admin.policies.update', $terms), [
                'title'     => $terms->title,
                'slug'      => $terms->slug ?: 'terms-conditions',
                'content'   => "KONTEN-BARU-DARI-ADMIN-PANEL\n\nParagraf kedua dari admin.",
                'version'   => '9.9',
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.policies.index'));

        // Buka ulang halaman register: modal harus menampilkan data terbaru.
        $response = $this->get(route('register'));

        $response->assertOk();
        $response->assertSee('KONTEN-BARU-DARI-ADMIN-PANEL', false);
        $response->assertSee('Paragraf kedua dari admin.', false);
        $response->assertSee('Versi: v9.9', false);
    }

    public function test_policy_content_is_escaped_inside_modal_like_existing_policy_pages(): void
    {
        $this->policy(Policy::KEY_PRIVACY)->update([
            'content' => "Baris pertama aman\n\n<b>tebalkan</b> & <script>alert(1)</script>",
        ]);

        $html = $this->get(route('register'))->getContent();

        // Konten policy tetap di-escape (mekanisme sama dengan halaman policy existing).
        $this->assertStringNotContainsString('<b>tebalkan</b>', $html);
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('&lt;b&gt;tebalkan&lt;/b&gt;', $html);
        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);

        // Baris baru tetap dirender sebagai paragraf (nl2br).
        $this->assertStringContainsString('Baris pertama aman', $html);
    }

    public function test_freelancer_registration_and_consent_are_unaffected_by_modal_ui(): void
    {
        $payload = [
            'name'                  => 'Siti Aminah',
            'email'                 => 'siti@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'phone'                 => '081298765432',
            'is_company'            => 0,
            'terms_accepted'        => '1',
            'privacy_accepted'      => '1',
            'usage_accepted'        => '1',
        ];

        $this->post(route('register'), $payload)
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $user = User::query()->where('email', 'siti@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('freelancer', $user->role);

        // UserConsent tetap tersimpan lengkap dengan versi policy.
        $this->assertSame(3, $user->consents()->count());
        $this->assertSame(
            $this->policy(Policy::KEY_TERMS)->version,
            $user->consents()->where('policy_id', $this->policy(Policy::KEY_TERMS)->id)->first()->policy_version
        );

        // Validasi consent wajib tetap berlaku di server.
        $invalid = $payload;
        $invalid['email'] = 'lain@example.com';
        unset($invalid['terms_accepted']);

        $this->post(route('register'), $invalid)
            ->assertSessionHasErrors('terms_accepted');
    }

    public function test_company_registration_is_unaffected_by_modal_ui(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Budi Santoso',
            'email'                 => 'company@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'is_company'            => 1,
            'company_name'          => 'PT Contoh Jaya',
            'company_phone'         => '081234567890',
            'company_address'       => 'Jl. Contoh No. 1, Jakarta',
            'terms_accepted'        => '1',
            'privacy_accepted'      => '1',
            'usage_accepted'        => '1',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'company@example.com',
            'role'  => 'company',
        ]);
        $this->assertDatabaseHas('company_account_requests', [
            'company_email'  => 'company@example.com',
            'request_status' => 'menunggu',
        ]);
    }
}
