<?php

namespace Tests\Feature;

use App\Http\Controllers\Company\PaymentController;
use App\Models\FinancialSetting;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use App\Models\WalletLedger;
use App\Models\Workspace;
use App\Services\AdminWalletService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ManualPaymentDemoTest â€” pembayaran manual/demo TANPA Midtrans.
 *
 * Jalur: config services.midtrans.temporary_confirmation (flag existing).
 * Keamanan: nominal SELALU dari database; owner-only; idempotent.
 */
class ManualPaymentDemoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.midtrans.server_key' => 'SB-Mid-server-TESTKEY123']);
    }

    /** Approved company (profil lengkap + akun disetujui) â€” syarat ensureCompanyAdminOrAbort. */
    private function approvedCompany(string $name = 'PT Demo Uji'): User
    {
        $company = User::factory()->create([
            'role'  => 'company',
            'phone' => '081234567890',
        ]);

        \App\Models\CompanyProfile::create([
            'user_id'      => $company->id,
            'company_name' => $name,
            'location'     => 'Jakarta',
        ]);

        \App\Models\CompanyAccountRequest::create([
            'company_name'   => $name,
            'contact_person' => $company->name,
            'company_email'  => $company->email,
            'company_phone'  => '081234567890',
            'company_address' => 'Jl. Uji Coba No. 1, Jakarta',
            'request_status' => 'disetujui',
        ]);

        return $company;
    }

    /** Payment proyek pending + workspace milik company. */
    private function createPendingWorkspacePayment(float $amount = 1000000.00): array
    {
        $company    = $this->approvedCompany('PT Pemilik Workspace');
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $project    = Project::factory()->create(['user_id' => $company->id]);

        $workspace = Workspace::create([
            'project_id'    => $project->id,
            'company_id'    => $company->id,
            'freelancer_id' => $freelancer->id,
            'status'        => 'Menunggu Pembayaran',
        ]);

        $platformFee       = round($amount * 5 / 100, 2);
        $freelancerReceive = round($amount - $platformFee, 2);

        $payment = Payment::create([
            'workspace_id'       => $workspace->id,
            'company_id'         => $company->id,
            'freelancer_id'      => $freelancer->id,
            'invoice_number'     => 'INV-' . now()->format('Ymd') . '-' . uniqid(),
            'amount'             => $amount,
            'platform_fee'       => $platformFee,
            'platform_fee_rate'  => 5.00,
            'freelancer_receive' => $freelancerReceive,
            'status'             => 'pending',
        ]);

        return [$payment, $workspace];
    }

    // â”€â”€â”€ FLAG OFF â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    public function test_manual_endpoints_are_disabled_when_demo_mode_off(): void
    {
        config(['services.midtrans.temporary_confirmation' => false]);

        [$payment, $workspace] = $this->createPendingWorkspacePayment();
        $quota = PaymentController::ensurePendingQuotaPayment($payment->company_id);

        $this->actingAs(User::find($payment->company_id))
            ->postJson(route('company.payments.confirm', ['workspace' => $workspace->id]))
            ->assertStatus(404);

        $this->actingAs(User::find($payment->company_id))
            ->postJson(route('company.quota.payment.confirm', ['payment' => $quota->id]))
            ->assertStatus(404);

        $this->assertEquals('pending', $payment->fresh()->status);
        $this->assertEquals(0, WalletLedger::count());
    }

    // â”€â”€â”€ WORKSPACE MANUAL CONFIRM â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    public function test_owner_can_manually_confirm_workspace_payment(): void
    {
        config(['services.midtrans.temporary_confirmation' => true]);
        [$payment, $workspace] = $this->createPendingWorkspacePayment();

        $this->actingAs(User::find($payment->company_id))
            ->postJson(route('company.payments.confirm', ['workspace' => $workspace->id]), [
                'amount' => 1, // percobaan manipulasi nominal dari client
                'status' => 'paid',
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        // Nominal tetap dari DATABASE, tidak dari request.
        $payment->refresh();
        $this->assertEquals('paid', $payment->status);
        $this->assertEquals(1000000.00, (float) $payment->amount);

        // Escrow ditahan SEKALI; belum ada income platform.
        $this->assertSame(1, WalletLedger::where('type', WalletLedger::TYPE_ESCROW_HELD)->count());
        $this->assertSame(0, WalletLedger::whereNull('user_id')->count());

        // Workspace dibuka.
        $this->assertEquals('Sedang Dikerjakan', $workspace->fresh()->status);
    }

    public function test_manual_confirm_is_idempotent_for_escrow(): void
    {
        config(['services.midtrans.temporary_confirmation' => true]);
        [$payment, $workspace] = $this->createPendingWorkspacePayment();
        $owner = User::find($payment->company_id);

        $this->actingAs($owner)->postJson(route('company.payments.confirm', ['workspace' => $workspace->id]))->assertOk();
        // Panggil ulang â€” harus no-op sukses, tanpa duplikasi ledger.
        $this->actingAs($owner)->postJson(route('company.payments.confirm', ['workspace' => $workspace->id]))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSame(1, WalletLedger::where('type', WalletLedger::TYPE_ESCROW_HELD)->count());
        $this->assertEquals(Payment::FUNDS_HELD, $payment->fresh()->funds_status);
    }

    public function test_non_owner_cannot_confirm_workspace_payment(): void
    {
        config(['services.midtrans.temporary_confirmation' => true]);
        [$payment, $workspace] = $this->createPendingWorkspacePayment();

        // Intruder berstatus company yang DISETUJUI — 403 murni karena bukan pemilik.
        $intruder = $this->approvedCompany('PT Penyusup Workspace');

        $this->actingAs($intruder)
            ->postJson(route('company.payments.confirm', ['workspace' => $workspace->id]))
            ->assertStatus(403);

        $payment->refresh();
        $this->assertEquals('pending', $payment->fresh()->status);
        $this->assertSame(0, WalletLedger::count());
    }

    // â”€â”€â”€ QUOTA MANUAL CONFIRM â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

    public function test_owner_can_manually_confirm_quota_payment_once(): void
    {
        config(['services.midtrans.temporary_confirmation' => true]);

        $company = $this->approvedCompany('PT Owner Kuota');
        $quota = PaymentController::ensurePendingQuotaPayment($company->id);

        $this->actingAs($company)
            ->postJson(route('company.quota.payment.confirm', ['payment' => $quota->id]))
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('status', 'paid');

        $this->assertEquals('paid', $quota->fresh()->status);
        $this->assertSame(1, WalletLedger::where('type', WalletLedger::TYPE_PROJECT_QUOTA_FEE)->count());

        $ledger = WalletLedger::where('type', WalletLedger::TYPE_PROJECT_QUOTA_FEE)->first();
        $this->assertEquals((float) $quota->amount, (float) $ledger->amount);
        $this->assertEquals((float) $quota->amount, AdminWalletService::balance());
    }

    public function test_quota_manual_confirm_is_idempotent(): void
    {
        config(['services.midtrans.temporary_confirmation' => true]);

        $company = $this->approvedCompany('PT Owner Kuota');
        $quota = PaymentController::ensurePendingQuotaPayment($company->id);

        $this->actingAs($company)->postJson(route('company.quota.payment.confirm', ['payment' => $quota->id]))->assertOk();
        $this->actingAs($company)->postJson(route('company.quota.payment.confirm', ['payment' => $quota->id]))
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSame(1, WalletLedger::where('type', WalletLedger::TYPE_PROJECT_QUOTA_FEE)->count());
        $this->assertEquals((float) $quota->amount, AdminWalletService::balance());
    }

    public function test_non_owner_cannot_confirm_quota_payment(): void
    {
        config(['services.midtrans.temporary_confirmation' => true]);

        $owner = $this->approvedCompany('PT Pemilik Kuota');
        $intruder = $this->approvedCompany('PT Penyusup');
        $quota = PaymentController::ensurePendingQuotaPayment($owner->id);

        $this->actingAs($intruder)
            ->postJson(route('company.quota.payment.confirm', ['payment' => $quota->id]))
            ->assertStatus(403);

        $this->assertEquals('pending', $quota->fresh()->status);
        $this->assertEquals(0.0, AdminWalletService::balance());
    }

    public function test_client_cannot_change_quota_amount_via_manual_confirm(): void
    {
        config(['services.midtrans.temporary_confirmation' => true]);

        FinancialSetting::create([
            'project_fee_rate' => 5,
            'withdrawal_fee_rate' => 5,
            'free_project_uploads_per_month' => 3,
            'paid_project_upload_price' => 15000,
        ]);

        $company = $this->approvedCompany('PT Bayar Manual');
        $quota = PaymentController::ensurePendingQuotaPayment($company->id);
        $this->assertEquals(15000.00, (float) $quota->amount);

        // Client mencoba membayar Rp1.
        $this->actingAs($company)
            ->postJson(route('company.quota.payment.confirm', ['payment' => $quota->id]), [
                'amount' => 1,
                'gross_amount' => '1.00',
            ])
            ->assertOk();

        $quota->refresh();
        $this->assertEquals('paid', $quota->status);
        $this->assertEquals(15000.00, (float) $quota->amount);

        $ledger = WalletLedger::where('type', WalletLedger::TYPE_PROJECT_QUOTA_FEE)->first();
        $this->assertEquals(15000.0, (float) $ledger->amount);
    }
}
