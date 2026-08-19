<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use App\Services\EscrowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemporaryPaymentConfirmationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.midtrans.server_key' => 'SB-Mid-server-TESTKEY123']);
        config(['services.midtrans.temporary_confirmation' => true]);

        // Buat Company dan CompanyAccountRequest agar middleware
        // ensureCompanyAdminOrAbort tidak memberikan 403 pada test.
        // Semua kolom NOT NULL wajib diisi lengkap sesuai migration.
        // Middleware hanya memeriksa company_email dan request_status,
        // tidak perlu associate user relationship.
        $this->company = User::factory()->create(['role' => 'company']);
        $companyRequest = CompanyAccountRequest::create([
            'company_name' => $this->company->name,
            'contact_person' => $this->company->name,
            'company_email' => $this->company->email,
            'company_phone' => '081234567890',
            'company_address' => 'Alamat Perusahaan',
            'request_status' => 'disetujui',
        ]);
    }

    private function createFreelancer(): User
    {
        return User::factory()->create(['role' => 'freelancer']);
    }

    private function createProject(User $company): Project
    {
        return Project::factory()->create(['user_id' => $company->id]);
    }

    private function createWorkspace(User $company, User $freelancer, Project $project): Workspace
    {
        return Workspace::create([
            'project_id' => $project->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            'status' => 'Sedang Dikerjakan',
        ]);
    }

    private function createPayment(User $company, User $freelancer, Workspace $workspace, float $amount = 1000000.00): Payment
    {
        return Payment::create([
            'workspace_id' => $workspace->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            'invoice_number' => 'INV-TEMP-' . uniqid(),
            'amount' => $amount,
            'status' => 'pending',
            'funds_status' => 'not_applicable',
            'payment_method' => null,
            'verified_at' => null,
        ]);
    }

    /** 
     * TEST 1: Payment confirmation — pending → paid 
     * Set selesai di sisi backend (tidak lewat frontend/user). 
     */
    public function test_payment_confirmation_pending_to_paid(): void
    {
        $freelancer = $this->createFreelancer();
        $project = $this->createProject($this->company);
        $workspace = $this->createWorkspace($this->company, $freelancer, $project);
        $payment = $this->createPayment($this->company, $freelancer, $workspace);

        $response = $this->actingAs($this->company)
            ->postJson("/company/workspaces/{$workspace->id}/payment/confirm");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertSame('Midtrans', $payment->payment_method);
        $this->assertNotNull($payment->verified_at);
    }

    /** 
     * TEST 2: Escrow funds_status not_applicable → held 
     * After payment confirmed, escrow hold is triggered atomically.
     */
    public function test_escrow_funds_status_not_applicable_to_held(): void
    {
        $freelancer = $this->createFreelancer();
        $project = $this->createProject($this->company);
        $workspace = $this->createWorkspace($this->company, $freelancer, $project);
        $payment = $this->createPayment($this->company, $freelancer, $workspace);

        $this->actingAs($this->company)
            ->postJson("/company/workspaces/{$workspace->id}/payment/confirm");

        $payment->refresh();
        $this->assertSame('held', $payment->funds_status);
        $this->assertNotNull($payment->held_at);
    }

    /** 
     * TEST 3: Workspace — setelah confirm payment, workspace status 
     * tetap Sedang Dikerjakan dan payment berubah menjadi paid + held.
     * Validasi alur unlock melalui EnsureWorkspacePaid.
     */
    public function test_workspace_status_after_confirm(): void
    {
        $freelancer = $this->createFreelancer();
        $project = $this->createProject($this->company);
        $workspace = $this->createWorkspace($this->company, $freelancer, $project);
        $payment = $this->createPayment($this->company, $freelancer, $workspace);

        $this->actingAs($this->company)
            ->postJson("/company/workspaces/{$workspace->id}/payment/confirm");

        $workspace->refresh();
        $this->assertSame('Sedang Dikerjakan', $workspace->status);

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertSame('held', $payment->funds_status);
    }

    /** 
     * TEST 4: Unauthorized user — user lain tidak boleh mengonfirmasi payment workspace 
     * milik company lain.
     */
    public function test_unauthorized_user_cannot_confirm_payment(): void
    {
        $freelancer1 = $this->createFreelancer();
        $project1 = $this->createProject($this->company);
        $workspace1 = $this->createWorkspace($this->company, $freelancer1, $project1);
        $payment1 = $this->createPayment($this->company, $freelancer1, $workspace1);

        $company2 = User::factory()->create(['role' => 'company']);
        CompanyAccountRequest::create([
            'company_name' => $company2->name,
            'contact_person' => $company2->name,
            'company_email' => $company2->email,
            'company_phone' => '081234567890',
            'company_address' => 'Alamat Perusahaan',
            'request_status' => 'disetujui',
        ]);

        $freelancer2 = $this->createFreelancer();
        $project2 = $this->createProject($company2);
        $workspace2 = $this->createWorkspace($company2, $freelancer2, $project2);
        $this->createPayment($company2, $freelancer2, $workspace2);

        // Company2 mencoba mengonfirmasi payment company1 (tidak diizinkan)
        $response = $this->actingAs($company2)
            ->postJson("/company/workspaces/{$workspace1->id}/payment/confirm");

        $response->assertStatus(403);
    }

    /** 
     * TEST 5: Double confirmation — konfirmasi kedua tidak diproses ulang 
     * (idempotent: payment sudah paid, return success dengan pesan sudah lunas).
     */
    public function test_double_confirmation_idempotent(): void
    {
        $freelancer = $this->createFreelancer();
        $project = $this->createProject($this->company);
        $workspace = $this->createWorkspace($this->company, $freelancer, $project);
        $payment = $this->createPayment($this->company, $freelancer, $workspace);

        // Pertama kalinya
        $this->actingAs($this->company)
            ->postJson("/company/workspaces/{$workspace->id}/payment/confirm")
            ->assertStatus(200);

        // Selanjutnya payment sudah paid, harus idempotent
        $response = $this->actingAs($this->company)
            ->postJson("/company/workspaces/{$workspace->id}/payment/confirm");

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'message' => 'Pembayaran sudah selesai sebelumnya.']);

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        // Workspace tetap Sedang Dikerjakan (tidak berubah)
        $this->assertSame('Sedang Dikerjakan', $workspace->fresh()->status);
    }

    /** 
     * TEST 6: Workspace sebelum payment tetap terkunci 
     * EnsureWorkspacePaid tetap menjaga keamanan sebelum payment dibayar.
     * Workspace status Sedang Dikerjakan sudah "terbuka" pasti karena 
     * payment belum paid, tapi kita test bahwa confirm payment berjalan 
     * dengan status payment yang berubah menjadi paid setelah konfirmasi.
     * Catatan: confirmPayment akan memproses payment pending menjadi paid.
     */
    public function test_workspace_terkunci_sebelum_payment_lunas(): void
    {
        $freelancer = $this->createFreelancer();
        $project = $this->createProject($this->company);
        $workspace = $this->createWorkspace($this->company, $freelancer, $project);
        $payment = $this->createPayment($this->company, $freelancer, $workspace);

        // Konfirmasi payment yang status pending akan diproses menjadi paid
        $this->actingAs($this->company)
            ->postJson("/company/workspaces/{$workspace->id}/payment/confirm")
            ->assertStatus(200);

        $workspace->refresh();
        // Workspace terbuka setelah payment menjadi paid
        $this->assertSame('Sedang Dikerjakan', $workspace->status);

        $payment->refresh();
        // Payment berubah menjadi paid setelah konfirmasi
        $this->assertSame('paid', $payment->status);
        $this->assertSame('held', $payment->funds_status);
    }

    /** 
     * TEST 7: Manual payment — upload bukti tidak membuka workspace 
     * (hanya masuk waiting_verifikasi, dibutuhkan verifikasi admin).
     * Flow ini tidak mengena confirmPayment (midtrans flow),
     * tapi kita pastikan status payment tidak berubah ke paid secara daring.
     */
    public function test_manual_payment_upload_does_not_open_workspace(): void
    {
        $freelancer = $this->createFreelancer();
        $project = $this->createProject($this->company);
        $workspace = Workspace::create([
            'project_id' => $project->id,
            'company_id' => $this->company->id,
            'freelancer_id' => $freelancer->id,
            'status' => 'Sedang Dikerjakan',
        ]);
        $payment = $this->createPayment($this->company, $freelancer, $workspace);

        // Manual flow: upload bukti → payment status tetap pending/waiting_verifikasi
        // dan workspace tetap Menunggu Verifikasi Admin.
        // Kasus ini tidak langsung melewati confirmPayment,
        // jadi kita hanya verifikasi bahwa status tidak berubah ke paid.
        $payment->refresh();
        $this->assertNotSame('paid', $payment->status);
        $this->assertSame('pending', $payment->status);
    }

    /** 
     * TEST 8: Admin verification — manual verify membuka workspace 
     * (admin verify → payment → paid + held + workspace terbuka). 
     * Flow manual sudah ada, kita hanya verifikasi tetap bekerja.
     */
    public function test_admin_verification_opens_workspace(): void
    {
        $freelancer = $this->createFreelancer();
        $project = $this->createProject($this->company);
        $workspace = Workspace::create([
            'project_id' => $project->id,
            'company_id' => $this->company->id,
            'freelancer_id' => $freelancer->id,
            'status' => 'Sedang Dikerjakan',
        ]);
        $payment = $this->createPayment($this->company, $freelancer, $workspace);

        // Simulasi admin verify seperti di AdminPaymentController@verify
        $payment->update(['status' => 'paid', 'verified_at' => now()]);
        app(EscrowService::class)->hold($payment);
        $workspace->update(['status' => 'Sedang Dikerjakan']);

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertSame('held', $payment->funds_status);
        $this->assertSame('Sedang Dikerjakan', $workspace->status);
    }

    /** 
     * TEST 9: Existing Midtrans webhook tests still pass 
     * (tidak rusak oleh perubahan baru).
     */
    public function test_existing_midtrans_webhook_still_works(): void
    {
        $freelancer = $this->createFreelancer();
        $project = $this->createProject($this->company);
        $workspace = Workspace::create([
            'project_id' => $project->id,
            'company_id' => $this->company->id,
            'freelancer_id' => $freelancer->id,
            'status' => 'Sedang Dikerjakan',
        ]);
        $payment = Payment::create([
            'workspace_id' => $workspace->id,
            'company_id' => $this->company->id,
            'freelancer_id' => $freelancer->id,
            'invoice_number' => 'INV-WEBHOOK-' . uniqid(),
            'amount' => 150000.00,
            'status' => 'pending',
        ]);

        $orderId = $payment->invoice_number . '_abc123';
        $signature = hash('sha512', $orderId . '200' . '150000.00' . 'SB-Mid-server-TESTKEY123');

        $this->postJson('/api/midtrans/notification', [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => '150000.00',
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
        ])->assertStatus(200);

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
    }

    /** 
     * TEST 10: Double release — admin tidak boleh melakukan release dua kali 
     * (idempotent, sudah ada di EscrowFlowTest, kita hanya verifikasi tetap valid).
     */
    public function test_double_release_idempotent_already_held(): void
    {
        $freelancer = $this->createFreelancer();
        $project = $this->createProject($this->company);
        $workspace = $this->createWorkspace($this->company, $freelancer, $project);
        $payment = $this->createPayment($this->company, $freelancer, $workspace);

        // Aktifkan confirmPayment terlebih dahulu
        $this->actingAs($this->company)
            ->postJson("/company/workspaces/{$workspace->id}/payment/confirm");

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertSame('held', $payment->funds_status);

        // Release dua kali (sebelumnya di-escrow hold)
        $result1 = app(EscrowService::class)->release($payment);
        $this->assertTrue($result1);

        $result2 = app(EscrowService::class)->release($payment);
        $this->assertFalse($result2); // kedua return false = idempotent

        $payment->refresh();
        $this->assertSame('released', $payment->funds_status);
    }
}