<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use App\Models\WalletLedger;
use App\Services\AdminWalletService;
use App\Services\ProjectQuotaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ProjectQuotaPaymentFlowTest — flow pembayaran kuota proyek tambahan.
 *
 * Memastikan:
 *   - 3 proyek pertama gratis dalam bulan berjalan.
 *   - Proyek ke-4 DIBLOKIR dan diarahkan ke GATEWAY pembayaran kuota
 *     (BUKAN langsung dibuat / dibayar otomatis).
 *   - Halaman gateway menampilkan detail & tombol bayar saat pending.
 *   - Payment hanya menjadi PAID lewat settlement Midtrans (webhook dengan
 *     signature valid) — tidak ada jalur auto-confirm.
 *   - Setelah paid: income Admin Wallet Rp10.000 tercatat SEKALI (idempotent,
 *     termasuk webhook duplikat), slot berbayar aktif, proyek ke-4 bisa dibuat.
 */
class ProjectQuotaPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.midtrans.server_key' => 'SB-Mid-server-TESTKEY123']);
    }

    /** Company dengan profil lengkap (≥80%) dan akun disetujui admin. */
    private function completeCompany(): User
    {
        $company = User::factory()->create([
            'role'  => 'company',
            'phone' => '081234567890',
        ]);

        CompanyProfile::create([
            'user_id'      => $company->id,
            'company_name' => 'PT Uji Coba',
            'location'     => 'Jakarta',
        ]);

        \App\Models\CompanyAccountRequest::create([
            'company_name'   => 'PT Uji Coba',
            'contact_person' => $company->name,
            'company_email'  => $company->email,
            'company_phone'  => '081234567890',
            'company_address' => 'Jl. Uji Coba No. 1, Jakarta',
            'request_status' => 'disetujui',
        ]);

        return $company;
    }

    private function projectPayload(): array
    {
        return [
            'project_name'        => 'Proyek Uji Kuota',
            'project_description' => 'Deskripsi proyek uji kuota.',
            'budget'              => 1000000,
            'deadline'            => now()->addDays(7)->toDateString(),
            'skills'              => 'PHP, Laravel',
            'status'              => Project::STATUS_OPEN,
        ];
    }

    private function settleViaWebhook(Payment $payment)
    {
        $signature = hash(
            'sha512',
            $payment->invoice_number . '200' . '10000.00' . config('services.midtrans.server_key')
        );

        return $this->postJson('/api/midtrans/notification', [
            'order_id'           => $payment->invoice_number,
            'status_code'        => '200',
            'gross_amount'       => '10000.00',
            'signature_key'      => $signature,
            'transaction_status' => 'settlement',
        ]);
    }

    // ────────────────────────────────────────────────────────────
    // TEST 1 — Proyek 1–3 gratis; proyek ke-4 DIBLOKIR + diarahkan
    // ke gateway kuota (TIDAK otomatis dibuat/dibayar).
    // ────────────────────────────────────────────────────────────
    public function test_three_projects_free_then_fourth_blocked_to_gateway(): void
    {
        $company = $this->completeCompany();
        $quotaService = new ProjectQuotaService();

        // 3 proyek bulan ini → kuota gratis (3/3) sudah terpakai penuh.
        Project::factory()->count(3)->create(['user_id' => $company->id]);

        $this->assertFalse($quotaService->canCreateProject($company->id));

        // Proyek ke-4 → diblokir server, diarahkan ke gateway kuota.
        $response = $this->actingAs($company)
            ->from(route('company.projects.create'))
            ->post(route('company.projects.store'), $this->projectPayload());

        $response->assertRedirect(route('company.projects.create'));
        $response->assertSessionHas('quota_payment_id');

        // TIDAK ada proyek baru yang tercipta.
        $this->assertSame(3, Project::where('user_id', $company->id)->count());
        $this->assertFalse($quotaService->canCreateProject($company->id));

        // Payment kuota pending dibuat oleh SERVER (nominal dari server).
        $payment = Payment::where('company_id', $company->id)
            ->where('payment_type', Payment::PAYMENT_TYPE_QUOTA)
            ->first();

        $this->assertNotNull($payment);
        $this->assertEquals('pending', $payment->status);
        $this->assertEquals(AdminWalletService::QUOTA_PRICE, (float) $payment->amount);
        $this->assertNull($payment->workspace_id);
        $this->assertNull($payment->freelancer_id);

        // Belum ada income Admin Wallet sebelum settlement.
        $this->assertEquals(0.0, AdminWalletService::balance());
        $this->assertSame(0, WalletLedger::where('type', WalletLedger::TYPE_PROJECT_QUOTA_FEE)->count());
    }

    // ────────────────────────────────────────────────────────────
    // TEST 2 — Halaman gateway tampil untuk payment pending,
    // memuat detail pembayaran & tombol Bayar (belum ada slot baru).
    // ────────────────────────────────────────────────────────────
    public function test_quota_gateway_page_renders_for_pending_payment(): void
    {
        $company = $this->completeCompany();
        Project::factory()->count(3)->create(['user_id' => $company->id]);

        $payment = \App\Http\Controllers\Company\PaymentController::ensurePendingQuotaPayment($company->id);

        $response = $this->actingAs($company)->get(route('company.quota.payment.show', $payment));

        $response->assertOk();
        $response->assertSee('Pembayaran Kuota Proyek');
        $response->assertSee($payment->invoice_number);
        $response->assertSee('Bayar Sekarang');
        $response->assertSee('Informasi Kuota Bulan Ini');

        // Ownership: company lain tidak boleh melihat gateway ini.
        $other = $this->completeCompany();
        $this->actingAs($other)
            ->get(route('company.quota.payment.show', $payment))
            ->assertForbidden();

        // Status tetap pending — belum dapat slot tambahan.
        $this->assertEquals('pending', $payment->fresh()->status);
        $this->assertFalse((new ProjectQuotaService())->canCreateProject($company->id));
    }

    // ────────────────────────────────────────────────────────────
    // TEST 3 — Settlement Midtrans (webhook, signature valid):
    // payment paid → income Admin Wallet sekali → slot aktif →
    // proyek ke-4 BERHASIL dibuat.
    // ────────────────────────────────────────────────────────────
    public function test_settlement_activates_paid_slot_and_fourth_project_succeeds(): void
    {
        $company = $this->completeCompany();
        $quotaService = new ProjectQuotaService();
        Project::factory()->count(3)->create(['user_id' => $company->id]);

        $blocked = $this->actingAs($company)
            ->post(route('company.projects.store'), $this->projectPayload());
        $blocked->assertRedirect(route('company.projects.create'));

        $payment = Payment::where('company_id', $company->id)
            ->where('payment_type', Payment::PAYMENT_TYPE_QUOTA)
            ->firstOrFail();

        // Sebelum settlement: belum bisa buat proyek.
        $this->assertFalse($quotaService->canCreateProject($company->id));
        $this->assertSame(0, $quotaService->paidSlotsThisMonth($company->id));

        // Webhook settlement Midtrans.
        $this->settleViaWebhook($payment)->assertStatus(200);

        $payment->refresh();
        $this->assertEquals('paid', $payment->status);

        // Income Admin Wallet: credit +Rp10.000, type project_quota_fee, user_id NULL.
        $ledger = WalletLedger::where('type', WalletLedger::TYPE_PROJECT_QUOTA_FEE)->first();
        $this->assertNotNull($ledger);
        $this->assertEquals('credit', $ledger->direction);
        $this->assertEquals(10000.0, (float) $ledger->amount);
        $this->assertNull($ledger->user_id);
        $this->assertEquals(10000.0, AdminWalletService::balance());

        // Slot berbayar terdeteksi dari DATABASE (bukan counter manual).
        $this->assertSame(1, $quotaService->paidSlotsThisMonth($company->id));
        $this->assertTrue($quotaService->canCreateProject($company->id));

        // Proyek ke-4 kini berhasil dibuat.
        $ok = $this->actingAs($company)
            ->post(route('company.projects.store'), $this->projectPayload());

        $ok->assertRedirect(route('company.dashboard'));
        $this->assertSame(4, Project::where('user_id', $company->id)->count());
    }

    // ────────────────────────────────────────────────────────────
    // TEST 4 — Webhook DUPLIKAT: income tetap tercatat satu kali.
    // ────────────────────────────────────────────────────────────
    public function test_duplicate_webhook_records_quota_income_once(): void
    {
        $company = $this->completeCompany();
        Project::factory()->count(3)->create(['user_id' => $company->id]);

        $this->actingAs($company)
            ->post(route('company.projects.store'), $this->projectPayload())
            ->assertRedirect(route('company.projects.create'));

        $payment = Payment::where('company_id', $company->id)
            ->where('payment_type', Payment::PAYMENT_TYPE_QUOTA)
            ->firstOrFail();

        // Webhook pertama → paid + 1 income.
        $this->settleViaWebhook($payment)->assertStatus(200);

        // Webhook kedua identik → no-op.
        $this->settleViaWebhook($payment)->assertStatus(200);

        $this->assertSame(1, WalletLedger::where('type', WalletLedger::TYPE_PROJECT_QUOTA_FEE)->count());
        $this->assertEquals(10000.0, AdminWalletService::balance());
        $this->assertEquals('paid', $payment->fresh()->status);
    }

    // ────────────────────────────────────────────────────────────
    // TEST 5 — Tidak ada jalur auto-confirm: klik "mulai bayar"
    // (endpoint start/show) TIDAK PERNAH mengubah status payment.
    // ────────────────────────────────────────────────────────────
    public function test_starting_payment_flow_never_marks_payment_paid(): void
    {
        $company = $this->completeCompany();
        Project::factory()->count(3)->create(['user_id' => $company->id]);

        $payment = \App\Http\Controllers\Company\PaymentController::ensurePendingQuotaPayment($company->id);

        // Endpoint start & show dipanggil — status HARUS tetap pending.
        $this->actingAs($company)->get(route('company.quota.payment.start'));
        $this->actingAs($company)->get(route('company.quota.payment.show', $payment));
        $this->actingAs($company)->get(route('company.quota.payment.status', $payment));

        $this->assertEquals('pending', $payment->fresh()->status);
        $this->assertEquals(0.0, AdminWalletService::balance());
        $this->assertSame(0, WalletLedger::where('type', WalletLedger::TYPE_PROJECT_QUOTA_FEE)->count());
    }
}
