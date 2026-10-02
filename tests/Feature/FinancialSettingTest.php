<?php

namespace Tests\Feature;

use App\Models\FinancialSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FinancialSettingTest — halaman & validasi Pengaturan Keuangan Admin.
 */
class FinancialSettingTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'project_fee_rate' => '5.00',
            'withdrawal_fee_rate' => '5.00',
            'free_project_uploads_per_month' => '3',
            'paid_project_upload_price' => '10000',
        ], $overrides);
    }

    // ─── AUTHORIZATION ─────────────────────────────────────────────

    public function test_guest_cannot_access_settings_page(): void
    {
        $this->get(route('admin.financial-settings.edit'))->assertRedirect();
        $this->assertGuest();
    }

    public function test_company_cannot_access_or_update_settings(): void
    {
        $company = User::factory()->create(['role' => 'company']);

        $this->actingAs($company)
            ->get(route('admin.financial-settings.edit'))
            ->assertStatus(403);

        $this->actingAs($company)
            ->put(route('admin.financial-settings.update'), $this->payload())
            ->assertStatus(403);

        $this->assertSame(0, FinancialSetting::count());
    }

    public function test_freelancer_cannot_access_or_update_settings(): void
    {
        $freelancer = User::factory()->create(['role' => 'freelancer']);

        $this->actingAs($freelancer)
            ->get(route('admin.financial-settings.edit'))
            ->assertStatus(403);

        $this->actingAs($freelancer)
            ->put(route('admin.financial-settings.update'), $this->payload())
            ->assertStatus(403);

        $this->assertSame(0, FinancialSetting::count());
    }

    // ─── PAGE ──────────────────────────────────────────────────────

    public function test_admin_can_open_settings_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.financial-settings.edit'))
            ->assertOk()
            ->assertSee('Fee Platform Proyek')
            ->assertSee('Fee Withdrawal Freelancer')
            ->assertSee('Batas Upload Gratis Proyek')
            ->assertSee('Harga Upload Berikutnya');
    }

    public function test_admin_can_update_all_settings(): void
    {
        $response = $this->actingAs($this->admin())
            ->put(route('admin.financial-settings.update'), $this->payload([
                'project_fee_rate' => '12.50',
                'withdrawal_fee_rate' => '7.50',
                'free_project_uploads_per_month' => '10',
                'paid_project_upload_price' => '25000',
            ]));

        $response->assertRedirect(route('admin.financial-settings.edit'))
            ->assertSessionHas('success', 'Pengaturan berhasil diperbarui.');

        $row = FinancialSetting::query()->firstOrFail();
        $this->assertEquals(12.50, (float) $row->project_fee_rate);
        $this->assertEquals(7.50, (float) $row->withdrawal_fee_rate);
        $this->assertEquals(10, $row->free_project_uploads_per_month);
        $this->assertEquals(25000.00, (float) $row->paid_project_upload_price);
    }

    public function test_updating_twice_keeps_single_row(): void
    {
        $admin = $this->admin();

        foreach ([['project_fee_rate' => '2'], ['project_fee_rate' => '8']] as $p) {
            $this->actingAs($admin)
                ->put(route('admin.financial-settings.update'), $this->payload($p))
                ->assertRedirect();
        }

        $this->assertSame(1, FinancialSetting::count());
        $this->assertEquals(8.00, FinancialSetting::getSettings()->projectFeeRate());
    }

    // ─── VALIDATION: RATES ─────────────────────────────────────────

    public function test_rate_zero_is_valid_for_both_fees(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.financial-settings.update'), $this->payload([
                'project_fee_rate' => '0',
                'withdrawal_fee_rate' => '0',
            ]))
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors();

        $row = FinancialSetting::getSettings();
        $this->assertEquals(0.0, $row->projectFeeRate());
        $this->assertEquals(0.0, $row->withdrawalFeeRate());
    }

    public function test_rate_100_is_valid(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.financial-settings.update'), $this->payload([
                'project_fee_rate' => '100',
                'withdrawal_fee_rate' => '100',
            ]))
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors();

        $row = FinancialSetting::getSettings();
        $this->assertEquals(100.0, $row->projectFeeRate());
        $this->assertEquals(100.0, $row->withdrawalFeeRate());
    }

    public function test_decimal_rate_12_50_is_valid(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.financial-settings.update'), $this->payload([
                'project_fee_rate' => '12.50',
                'withdrawal_fee_rate' => '2.75',
            ]))
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors();
    }

    public function test_invalid_rates_are_rejected(): void
    {
        // Negatif, > max, tiga desimal, dan non-angka.
        foreach (['-1', '-0.5', '100.01', '12.505', 'abc'] as $bad) {
            $this->actingAs($this->admin())
                ->put(route('admin.financial-settings.update'), $this->payload([
                    'project_fee_rate' => $bad,
                    'withdrawal_fee_rate' => '5.00',
                ]))
                ->assertRedirect()
                ->assertSessionHasErrors('project_fee_rate');
        }
    }

    public function test_negative_withdrawal_rate_rejected(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.financial-settings.update'), $this->payload([
                'withdrawal_fee_rate' => '-7.5',
            ]))
            ->assertRedirect()
            ->assertSessionHasErrors('withdrawal_fee_rate');
    }

    // ─── VALIDATION: UPLOAD LIMIT & PRICE ──────────────────────────

    public function test_zero_free_uploads_and_price_is_valid(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.financial-settings.update'), $this->payload([
                'free_project_uploads_per_month' => '0',
                'paid_project_upload_price' => '0',
            ]))
            ->assertRedirect()
            ->assertSessionDoesntHaveErrors();

        $row = FinancialSetting::getSettings();
        $this->assertSame(0, $row->freeUploadsPerMonth());
        $this->assertEquals(0.0, $row->paidUploadPrice());
    }

    public function test_negative_and_fractional_limits_rejected(): void
    {
        foreach (['-1', '2.5'] as $bad) {
            $this->actingAs($this->admin())
                ->put(route('admin.financial-settings.update'), $this->payload([
                    'free_project_uploads_per_month' => $bad,
                ]))
                ->assertRedirect()
                ->assertSessionHasErrors('free_project_uploads_per_month');
        }
    }

    public function test_negative_price_rejected(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.financial-settings.update'), $this->payload([
                'paid_project_upload_price' => '-5000',
            ]))
            ->assertRedirect()
            ->assertSessionHasErrors('paid_project_upload_price');
    }
}
