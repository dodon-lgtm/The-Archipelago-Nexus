<?php

namespace Tests\Feature;

use App\Http\Controllers\Company\PaymentController;
use App\Models\CompanyAccountRequest;
use App\Models\CompanyProfile;
use App\Models\FinancialSetting;
use App\Models\Payment;
use App\Models\Penawaran;
use App\Models\Project;
use App\Models\User;
use App\Services\WithdrawalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * FinancialSnapshotTest — snapshot rate/harga pada saat transaksi dibuat.
 *
 * Inti aturan: mengubah Financial Settings TIDAK BOLEH mengubah transaksi lama.
 */
class FinancialSnapshotTest extends TestCase
{
    use RefreshDatabase;

    private function setSettings(
        float $projectFee = 5,
        float $withdrawalFee = 5,
        int $freeUploads = 3,
        float $uploadPrice = 10000
    ): void {
        FinancialSetting::query()->delete();
        FinancialSetting::create([
            'project_fee_rate' => $projectFee,
            'withdrawal_fee_rate' => $withdrawalFee,
            'free_project_uploads_per_month' => $freeUploads,
            'paid_project_upload_price' => $uploadPrice,
        ]);
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

        CompanyAccountRequest::create([
            'company_name'   => 'PT Uji Coba',
            'contact_person' => $company->name,
            'company_email'  => $company->email,
            'company_phone'  => '081234567890',
            'company_address' => 'Jl. Uji Coba No. 1, Jakarta',
            'request_status' => 'disetujui',
        ]);

        return $company;
    }

    private function acceptOffer(User $company, float $harga): Payment
    {
        $project = Project::factory()->create(['user_id' => $company->id]);
        $freelancer = User::factory()->create(['role' => 'freelancer']);

        $penawaran = Penawaran::create([
            'project_id'      => $project->id,
            'freelancer_id'   => $freelancer->id,
            'harga_penawaran' => $harga,
            'estimasi_hari'   => 7,
            'pesan'           => 'Siap mengerjakan.',
            'status'          => 'Menunggu',
        ]);

        $this->actingAs($company)
            ->post(route('company.projects.penawaran.select', [
                'project'   => $project->id,
                'penawaran' => $penawaran->id,
            ]))
            ->assertRedirect();

        return Payment::where('workspace_id', $project->workspace->id)->firstOrFail();
    }

    // ─── PROJECT FEE SNAPSHOT ──────────────────────────────────────

    public function test_new_project_payment_uses_default_5_percent_without_settings_row(): void
    {
        $payment = $this->acceptOffer($this->completeCompany(), 2000000);

        $this->assertEquals(5.00, (float) $payment->platform_fee_rate);
        $this->assertEquals(100000.00, (float) $payment->platform_fee);
        $this->assertEquals(1900000.00, (float) $payment->freelancer_receive);
    }

    public function test_project_fee_change_does_not_rewrite_existing_payment(): void
    {
        $old = $this->acceptOffer($this->completeCompany(), 2000000);
        $this->assertEquals(5.00, (float) $old->platform_fee_rate);

        // Admin menaikkan fee menjadi 12.5%.
        $this->setSettings(projectFee: 12.50);

        // Payment lama tetap memakai rate lama (snapshot).
        $old->refresh();
        $this->assertEquals(5.00, (float) $old->platform_fee_rate);
        $this->assertEquals(100000.00, (float) $old->platform_fee);
        $this->assertEquals(1900000.00, (float) $old->freelancer_receive);
    }

    public function test_new_project_payment_uses_updated_fee_rate(): void
    {
        $this->setSettings(projectFee: 12.50);

        $payment = $this->acceptOffer($this->completeCompany(), 2000000);

        $this->assertEquals(12.50, (float) $payment->platform_fee_rate);
        $this->assertEquals(250000.00, (float) $payment->platform_fee);
        $this->assertEquals(1750000.00, (float) $payment->freelancer_receive);
    }

    // ─── WITHDRAWAL FEE SNAPSHOT ───────────────────────────────────

    private function fundFreelancer(User $freelancer, float $receive): void
    {
        Payment::create([
            'workspace_id'       => null,
            'company_id'         => User::factory()->create(['role' => 'company'])->id,
            'freelancer_id'      => $freelancer->id,
            'invoice_number'     => 'INV-TST-' . uniqid(),
            'amount'             => $receive + 1000,
            'platform_fee'       => 1000,
            'freelancer_receive' => $receive,
            'status'             => 'paid',
            'funds_status'       => Payment::FUNDS_RELEASED,
            'released_amount'    => $receive,
        ]);
    }

    public function test_withdrawal_uses_rate_at_creation_and_old_withdrawal_stays(): void
    {
        $service = app(WithdrawalService::class);

        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $this->fundFreelancer($freelancer, 500000);

        // Rate default (tanpa row) = 5%.
        $old = $service->store([
            'amount'         => 100000,
            'method'         => 'bank',
            'bank_name'      => 'BCA',
            'account_name'   => $freelancer->name,
            'account_number' => '1234567890',
        ], $freelancer->id);

        $this->assertEquals(5.00, (float) $old->fee_rate);
        $this->assertEquals(5000.00, (float) $old->fee);
        $this->assertEquals(95000.00, (float) $old->net_amount);

        // Admin mengubah fee menjadi 10%.
        $this->setSettings(withdrawalFee: 10);

        $new = $service->store([
            'amount'         => 100000,
            'method'         => 'bank',
            'bank_name'      => 'BNI',
            'account_name'   => $freelancer->name,
            'account_number' => '9876543210',
        ], $freelancer->id);

        $this->assertEquals(10.00, (float) $new->fee_rate);
        $this->assertEquals(10000.00, (float) $new->fee);
        $this->assertEquals(90000.00, (float) $new->net_amount);

        // Withdrawal lama TIDAK berubah.
        $old->refresh();
        $this->assertEquals(5.00, (float) $old->fee_rate);
        $this->assertEquals(5000.00, (float) $old->fee);
        $this->assertEquals(95000.00, (float) $old->net_amount);
    }

    // ─── QUOTA PRICE SNAPSHOT ──────────────────────────────────────

    public function test_quota_price_change_creates_new_pending_payment(): void
    {
        $company = $this->completeCompany();

        // Tanpa row → default Rp10.000.
        $first = PaymentController::ensurePendingQuotaPayment($company->id);
        $this->assertEquals(10000.00, (float) $first->amount);

        // Admin menaikkan harga upload menjadi Rp15.000.
        $this->setSettings(uploadPrice: 15000);

        // Price berubah → payment BARU dibuat dengan harga baru.
        // Payment lama (pending) tetap immutable di DB.
        $second = PaymentController::ensurePendingQuotaPayment($company->id);
        $this->assertNotSame($first->id, $second->id);
        $this->assertEquals(15000.00, (float) $second->amount);

        // Payment LAMA tetap immutable: amount = 10000, status = pending
        $this->assertEquals(10000.00, (float) $first->fresh()->amount);
        $this->assertEquals('pending', $first->fresh()->status);

        // Sumber harga server kini mengikuti settings.
        $this->assertEquals(15000.0, \App\Services\AdminWalletService::quotaPrice());

        // Panggil lagi → harus REUSE payment kedua (pending dengan harga current)
        $reused = PaymentController::ensurePendingQuotaPayment($company->id);
        $this->assertSame($second->id, $reused->id);
        $this->assertEquals(15000.00, (float) $reused->amount);

        // Setelah payment kedua tidak lagi aktif (mis. rejected), payment BARU memakai harga baru.
        $second->update(['status' => 'rejected']);
        $third = PaymentController::ensurePendingQuotaPayment($company->id);
        $this->assertNotSame($second->id, $third->id);
        $this->assertEquals(15000.00, (float) $third->amount);
    }

    public function test_paid_quota_payment_not_affected_by_price_change(): void
    {
        $company = $this->completeCompany();

        // Harga awal 10000
        $payment = PaymentController::ensurePendingQuotaPayment($company->id);
        $this->assertEquals(10000.00, (float) $payment->amount);

        // Simulasikan payment PAID
        $payment->update(['status' => 'paid', 'verified_at' => now()]);

        // Admin ubah harga ke 15000
        $this->setSettings(uploadPrice: 15000);

        // Payment yang sudah PAID TIDAK BERUBAH (snapshot)
        $payment->refresh();
        $this->assertEquals('paid', $payment->status);
        $this->assertEquals(10000.00, (float) $payment->amount);

        // Payment QUOTA BARU (untuk proyek selanjutnya) pakai harga 15000
        $newPayment = PaymentController::ensurePendingQuotaPayment($company->id);
        $this->assertEquals(15000.00, (float) $newPayment->amount);
    }
}
