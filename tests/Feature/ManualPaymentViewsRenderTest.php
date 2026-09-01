<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use App\Models\WalletLedger;
use App\Models\Workspace;
use App\Services\AdminWalletService;
use App\Services\ProjectQuotaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * ManualPaymentViewsRenderTest — temp test untuk memverifikasi view Blade
 * 'company.payments.upload' dan 'admin.payments.show' tampil (via route GET,
 * sehingga $errors dinijektasi oleh HTTP lifecycle).
 */
class ManualPaymentViewsRenderTest extends TestCase
{
    use RefreshDatabase;

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

    private function createPendingWorkspacePayment(): array
    {
        $company    = $this->completeCompany();
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $project    = Project::factory()->create(['user_id' => $company->id]);

        $workspace = Workspace::create([
            'project_id'    => $project->id,
            'company_id'    => $company->id,
            'freelancer_id' => $freelancer->id,
            'status'        => 'Menunggu Pembayaran',
        ]);

        $payment = Payment::create([
            'workspace_id'       => $workspace->id,
            'company_id'         => $company->id,
            'freelancer_id'      => $freelancer->id,
            'invoice_number'     => 'INV-TEST-' . uniqid(),
            'amount'             => 1000000.00,
            'platform_fee'       => 50000.00,
            'freelancer_receive' => 950000.00,
            'status'             => 'pending',
        ]);

        return [$payment, $workspace];
    }

    public function test_company_manual_payment_view_renders(): void
    {
        [$payment, $workspace] = $this->createPendingWorkspacePayment();

        $response = $this->actingAs(\App\Models\User::find($payment->company_id))
            ->get(route('company.payments.upload-form', $workspace));

        $response->assertOk();
        $response->assertSee('Rekening / Wallet Tujuan Pembayaran');
        $response->assertSee('Salin');
        $response->assertSee('Saya Sudah Membayar');
        $response->assertSee($payment->invoice_number);
        $response->assertSee(number_format($payment->amount, 0, ',', '.'));

        // Radio destination_source harus ter-asosiasi ke form konfirmasi
        // (atribut HTML5 form="manualPaymentForm") agar ikut ter-submit.
        $content = $response->getContent();
        $this->assertStringContainsString('id="manualPaymentForm"', $content);
        $this->assertMatchesRegularExpression(
            '/name="destination_source"[^>]*form="manualPaymentForm"/',
            $content
        );
    }

    public function test_admin_show_payment_view_renders(): void
    {
        [$payment, $workspace] = $this->createPendingWorkspacePayment();

        $payment->update([
            'payment_method'       => 'Transfer Bank',
            'sender_name'          => 'PT Pemilik',
            'sender_bank'          => 'BCA',
            'sender_account_number' => '1234567890',
            'payment_date'         => now()->toDateString(),
            'paid_amount'          => 1000000.00,
            'destination_info'     => [
                'title' => 'BANK',
                'label' => 'Transfer Bank',
                'rows'  => ['Nama Bank' => 'Bank Central Asia', 'Nomor Rekening' => '1234567890', 'Atas Nama' => 'PT ApexForge Labs'],
            ],
            'status'               => 'waiting_verification',
        ]);
        $workspace->update(['status' => 'Menunggu Verifikasi Admin']);

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.payments.show', $payment));

        $response->assertOk();
        $response->assertSee('Informasi Pembayaran Manual');
        $response->assertSee('PT Pemilik');
        $response->assertSee('Rekening/Wallet Tujuan yang Dipakai');
    }

    public function test_company_can_submit_manual_payment_confirmation(): void
    {
        Storage::fake('public');
        [$payment, $workspace] = $this->createPendingWorkspacePayment();

        $response = $this->actingAs(\App\Models\User::find($payment->company_id))
            ->post(route('company.payments.upload', $workspace), [
                'payment_method'        => 'Transfer Bank',
                'destination_source'    => 'bank',
                'sender_name'           => 'PT Pengirim',
                'sender_bank'           => 'BCA',
                'sender_account_number' => '1234567890',
                'payment_date'          => now()->toDateString(),
                'paid_amount'           => (float) $payment->amount,
                'company_note'          => 'Sudah transfer via m-banking.',
                'payment_proof'         => UploadedFile::fake()->image('bukti.jpg'),
            ]);

        $response->assertRedirect(route('company.workspaces.show', $workspace));
        $response->assertSessionHas('success');

        $payment->refresh();
        $this->assertSame('waiting_verification', $payment->status);
        $this->assertSame('Transfer Bank', $payment->payment_method);
        $this->assertSame('PT Pengirim', $payment->sender_name);
        $this->assertSame('BCA', $payment->sender_bank);
        $this->assertSame('1234567890', $payment->sender_account_number);
        $this->assertNotNull($payment->payment_date);
        $this->assertEquals((float) $payment->amount, (float) $payment->paid_amount);
        $this->assertStringStartsWith('payment-proofs/', $payment->payment_proof);
        $this->assertIsArray($payment->destination_info);
        $this->assertSame('BANK', $payment->destination_info['title'] ?? null);

        $workspace->refresh();
        $this->assertSame('Menunggu Verifikasi Admin', $workspace->status);

        Storage::disk('public')->assertExists($payment->payment_proof);
    }

    public function test_company_cannot_change_nominal_payment(): void
    {
        Storage::fake('public');
        [$payment, $workspace] = $this->createPendingWorkspacePayment();

        $response = $this->actingAs(\App\Models\User::find($payment->company_id))
            ->post(route('company.payments.upload', $workspace), [
                'payment_method'     => 'Transfer Bank',
                'destination_source' => 'bank',
                'sender_name'        => 'PT Pengirim',
                'sender_bank'        => 'BCA',
                'payment_date'       => now()->toDateString(),
                'paid_amount'        => 1.00, // nominal tidak boleh diubah
                'payment_proof'      => UploadedFile::fake()->image('bukti.jpg'),
            ]);

        $response->assertSessionHasErrors('paid_amount');

        $payment->refresh();
        $this->assertSame('pending', $payment->status);
        $this->assertNull($payment->sender_name);
    }

    public function test_non_owner_cannot_upload_payment_proof(): void
    {
        Storage::fake('public');
        [$payment, $workspace] = $this->createPendingWorkspacePayment();
        $intruder = $this->completeCompany();

        $response = $this->actingAs($intruder)
            ->post(route('company.payments.upload', $workspace), [
                'payment_method'     => 'Transfer Bank',
                'destination_source' => 'bank',
                'sender_name'        => 'Penyusup',
                'sender_bank'        => 'BCA',
                'payment_date'       => now()->toDateString(),
                'paid_amount'        => (float) $payment->amount,
                'payment_proof'      => UploadedFile::fake()->image('bukti.jpg'),
            ]);

        $response->assertForbidden();

        $payment->refresh();
        $this->assertSame('pending', $payment->status);
    }

    // ─── PEMBAYARAN MANUAL KUOTA PROYEK ─────────────────────────────

    private function createQuotaPayment(User $company): Payment
    {
        return \App\Http\Controllers\Company\PaymentController::ensurePendingQuotaPayment($company->id);
    }

    public function test_quota_gateway_renders_manual_payment_section(): void
    {
        $company = $this->completeCompany();
        $payment = $this->createQuotaPayment($company);

        $response = $this->actingAs($company)->get(route('company.quota.payment.show', $payment));

        $response->assertOk();
        $response->assertSee('Rekening / Wallet Tujuan Pembayaran');
        $response->assertSee('Bayar Manual');
        $response->assertSee('Saya Sudah Membayar');
        $response->assertSee(route('company.quota.payment.manual', $payment));
        $response->assertSee('name="destination_source"', false);
        $response->assertSee('name="sender_name"', false);
        $response->assertSee('name="payment_proof"', false);
    }

    public function test_company_can_submit_manual_quota_payment(): void
    {
        Storage::fake('public');
        $company = $this->completeCompany();
        $payment = $this->createQuotaPayment($company);

        $response = $this->actingAs($company)
            ->post(route('company.quota.payment.manual', $payment), [
                'payment_method'        => 'Transfer Bank',
                'destination_source'    => 'bank',
                'sender_name'           => 'PT Pengirim Kuota',
                'sender_bank'           => 'BCA',
                'sender_account_number' => '9876543210',
                'payment_date'          => now()->toDateString(),
                'paid_amount'           => (float) $payment->amount,
                'company_note'          => 'Transfer kuota.',
                'payment_proof'         => UploadedFile::fake()->image('bukti-kuota.jpg'),
            ]);

        $response->assertRedirect(route('company.quota.payment.show', $payment));
        $response->assertSessionHas('success');

        $payment->refresh();
        $this->assertSame('waiting_verification', $payment->status);
        $this->assertSame('Transfer Bank', $payment->payment_method);
        $this->assertSame('PT Pengirim Kuota', $payment->sender_name);
        $this->assertSame('BCA', $payment->sender_bank);
        $this->assertEquals((float) $payment->amount, (float) $payment->paid_amount);
        $this->assertStringStartsWith('payment-proofs/', $payment->payment_proof);
        $this->assertIsArray($payment->destination_info);
        $this->assertSame('BANK', $payment->destination_info['title'] ?? null);
        $this->assertNull($payment->workspace_id);

        Storage::disk('public')->assertExists($payment->payment_proof);
    }

    public function test_company_cannot_change_quota_nominal(): void
    {
        Storage::fake('public');
        $company = $this->completeCompany();
        $payment = $this->createQuotaPayment($company);

        $this->actingAs($company)
            ->post(route('company.quota.payment.manual', $payment), [
                'payment_method'     => 'Transfer Bank',
                'destination_source' => 'bank',
                'sender_name'        => 'PT Pengirim Kuota',
                'sender_bank'        => 'BCA',
                'payment_date'       => now()->toDateString(),
                'paid_amount'        => 1.00, // nominal dari client ditolak
                'payment_proof'      => UploadedFile::fake()->image('bukti-kuota.jpg'),
            ])
            ->assertSessionHasErrors('paid_amount');

        $payment->refresh();
        $this->assertSame('pending', $payment->status);
        $this->assertNull($payment->sender_name);
    }

    public function test_non_owner_cannot_submit_quota_manual_payment(): void
    {
        Storage::fake('public');
        $company = $this->completeCompany();
        $payment = $this->createQuotaPayment($company);
        $intruder = $this->completeCompany();

        $this->actingAs($intruder)
            ->post(route('company.quota.payment.manual', $payment), [
                'payment_method'     => 'Transfer Bank',
                'destination_source' => 'bank',
                'sender_name'        => 'Penyusup',
                'sender_bank'        => 'BCA',
                'payment_date'       => now()->toDateString(),
                'paid_amount'        => (float) $payment->amount,
                'payment_proof'      => UploadedFile::fake()->image('bukti.jpg'),
            ])
            ->assertForbidden();

        $payment->refresh();
        $this->assertSame('pending', $payment->status);
    }

    public function test_quota_payment_proof_is_required(): void
    {
        Storage::fake('public');
        $company = $this->completeCompany();
        $payment = $this->createQuotaPayment($company);

        $this->actingAs($company)
            ->post(route('company.quota.payment.manual', $payment), [
                'payment_method'     => 'Transfer Bank',
                'destination_source' => 'bank',
                'sender_name'        => 'PT Pengirim Kuota',
                'sender_bank'        => 'BCA',
                'payment_date'       => now()->toDateString(),
                'paid_amount'        => (float) $payment->amount,
                // payment_proof sengaja tidak dikirim
            ])
            ->assertSessionHasErrors('payment_proof');

        $payment->refresh();
        $this->assertSame('pending', $payment->status);
        $this->assertNull($payment->payment_proof);
    }

    public function test_admin_can_verify_manual_quota_payment_and_slot_activates(): void
    {
        Storage::fake('public');
        $company = $this->completeCompany();
        $payment = $this->createQuotaPayment($company);

        // Company kirim pembayaran manual.
        $this->actingAs($company)
            ->post(route('company.quota.payment.manual', $payment), [
                'payment_method'     => 'Transfer Bank',
                'destination_source' => 'bank',
                'sender_name'        => 'PT Pengirim Kuota',
                'sender_bank'        => 'BCA',
                'payment_date'       => now()->toDateString(),
                'paid_amount'        => (float) $payment->amount,
                'payment_proof'      => UploadedFile::fake()->image('bukti-kuota.jpg'),
            ])->assertSessionHas('success');

        $admin = User::factory()->create(['role' => 'admin']);

        // Admin verify — workflow verifikasi kuota yang sudah ada.
        $this->actingAs($admin)
            ->post(route('admin.payments.verify', $payment))
            ->assertRedirect(route('admin.payments.show', $payment));

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertNotNull($payment->verified_at);
        $this->assertSame(1, WalletLedger::where('type', WalletLedger::TYPE_PROJECT_QUOTA_FEE)->count());
        $this->assertEquals((float) $payment->amount, AdminWalletService::balance());

        // Slot kuota aktif: setelah 3 proyek terpakai, proyek ke-4 bisa dibuat.
        Project::factory()->count(3)->create(['user_id' => $company->id]);
        $quotaService = new ProjectQuotaService();
        $this->assertSame(1, $quotaService->paidSlotsThisMonth($company->id));
        $this->assertTrue($quotaService->canCreateProject($company->id));
    }

    public function test_admin_can_reject_manual_quota_payment(): void
    {
        Storage::fake('public');
        $company = $this->completeCompany();
        $payment = $this->createQuotaPayment($company);

        $this->actingAs($company)
            ->post(route('company.quota.payment.manual', $payment), [
                'payment_method'     => 'Transfer Bank',
                'destination_source' => 'bank',
                'sender_name'        => 'PT Pengirim Kuota',
                'sender_bank'        => 'BCA',
                'payment_date'       => now()->toDateString(),
                'paid_amount'        => (float) $payment->amount,
                'payment_proof'      => UploadedFile::fake()->image('bukti-kuota.jpg'),
            ])->assertSessionHas('success');

        $admin = User::factory()->create(['role' => 'admin']);

        // Admin reject — guard null-workspace tidak boleh fatal untuk kuota.
        $this->actingAs($admin)
            ->post(route('admin.payments.reject', $payment->id), [
                'admin_note' => 'Bukti tidak jelas.',
            ])
            ->assertRedirect(route('admin.payments.show', $payment));

        $payment->refresh();
        $this->assertSame('rejected', $payment->status);

        // Company dapat mengirim ulang setelah ditolak.
        $this->actingAs($company)
            ->post(route('company.quota.payment.manual', $payment), [
                'payment_method'     => 'Transfer Bank',
                'destination_source' => 'bank',
                'sender_name'        => 'PT Pengirim Kuota',
                'sender_bank'        => 'BCA',
                'payment_date'       => now()->toDateString(),
                'paid_amount'        => (float) $payment->amount,
                'payment_proof'      => UploadedFile::fake()->image('bukti-kuota-2.jpg'),
            ])
            ->assertSessionHas('success');

        $payment->refresh();
        $this->assertSame('waiting_verification', $payment->status);
    }
}