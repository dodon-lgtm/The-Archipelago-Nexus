<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectSubmission;
use App\Models\User;
use App\Models\WalletLedger;
use App\Models\Workspace;
use App\Services\EscrowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EscrowFlowTest extends TestCase
{
    use RefreshDatabase;

    protected EscrowService $escrow;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.midtrans.server_key' => 'SB-Mid-server-TESTKEY123']);
        $this->escrow = app(EscrowService::class);
    }

    /**
     * Payment lunas dengan model fee REVISI #5:
     *   $amount = NILAI PEKERJAAN (accepted offer); fee 5% DITAMBAHKAN DI ATAS.
     *   payments.amount             = total dibayar company (pekerjaan + fee)
     *   payments.freelancer_receive = nilai pekerjaan penuh
     */
    private function createPaidPayment(float $amount = 1000000.00): Payment
    {
        $company = User::factory()->create(['role' => 'company']);
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $project = Project::factory()->create(['user_id' => $company->id]);
        $workspace = Workspace::create([
            'project_id' => $project->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            'status' => 'Sedang Dikerjakan',
        ]);

        $freelancerReceive = round($amount, 2);
        $fee = round($freelancerReceive * 0.05, 2);
        $total = round($freelancerReceive + $fee, 2);

        return Payment::create([
            'workspace_id' => $workspace->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            'invoice_number' => 'INV-ESCROW-' . uniqid(),
            'amount' => $total,
            'platform_fee' => $fee,
            'platform_fee_rate' => 5.00,
            'freelancer_receive' => $freelancerReceive,
            'status' => 'paid',
            'verified_at' => now(),
        ]);
    }

    public function test_hold_sets_funds_held_and_creates_escrow_held_ledger(): void
    {
        $payment = $this->createPaidPayment();

        $result = $this->escrow->hold($payment);

        $this->assertTrue($result);
        $payment->refresh();
        $this->assertSame('held', $payment->funds_status);
        $this->assertNotNull($payment->held_at);

        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_ESCROW_HELD,
            'amount' => '1050000.00',
            'direction' => WalletLedger::DIRECTION_DEBIT,
            'user_id' => $payment->company_id,
        ]);
    }

    public function test_hold_is_idempotent_no_double_ledger(): void
    {
        $payment = $this->createPaidPayment();
        $this->escrow->hold($payment);

        // Hold kedua -> no-op, tidak ada ledger duplikat
        $this->assertFalse($this->escrow->hold($payment));

        $this->assertSame(1, WalletLedger::where('payment_id', $payment->id)
            ->where('type', WalletLedger::TYPE_ESCROW_HELD)
            ->count());
    }

    public function test_release_full_sets_released_and_creates_release_and_fee_ledger(): void
    {
        $payment = $this->createPaidPayment();
        $this->escrow->hold($payment);

        $result = $this->escrow->release($payment);

        $this->assertTrue($result);
        $payment->refresh();
        $this->assertSame('released', $payment->funds_status);
        $this->assertSame('1000000.00', $payment->released_amount);
        $this->assertNotNull($payment->released_at);

        // Ledger: escrow_released untuk freelancer + fee_earned untuk platform
        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_ESCROW_RELEASED,
            'amount' => '1000000.00',
            'direction' => WalletLedger::DIRECTION_CREDIT,
            'user_id' => $payment->freelancer_id,
        ]);
        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_FEE_EARNED,
            'amount' => '50000.00',
        ]);
    }

    public function test_double_release_is_idempotent_no_double_ledger(): void
    {
        $payment = $this->createPaidPayment();
        $this->escrow->hold($payment);
        $this->escrow->release($payment);

        // Release kedua -> no-op (ditolak/diabaikan dengan aman)
        $this->assertFalse($this->escrow->release($payment));

        $this->assertSame(1, WalletLedger::where('payment_id', $payment->id)
            ->where('type', WalletLedger::TYPE_ESCROW_RELEASED)
            ->count());
        $this->assertSame('released', $payment->fresh()->funds_status);
    }

    public function test_refund_full_sets_refunded_with_full_amount_and_refund_ledger(): void
    {
        $payment = $this->createPaidPayment();
        $this->escrow->hold($payment);

        $this->assertTrue($this->escrow->refund($payment));

        $payment->refresh();
        $this->assertSame('refunded', $payment->funds_status);
        $this->assertSame('1050000.00', $payment->refunded_amount);

        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_REFUND_ISSUED,
            'amount' => '1050000.00',
            'direction' => WalletLedger::DIRECTION_CREDIT,
            'user_id' => $payment->company_id,
        ]);

        // Refund penuh: fee platform tidak diambil
        $this->assertSame(0, WalletLedger::where('payment_id', $payment->id)
            ->where('type', WalletLedger::TYPE_FEE_EARNED)
            ->count());
    }

    public function test_partial_split_creates_release_and_refund_ledgers_no_missing_amount(): void
    {
        $payment = $this->createPaidPayment();
        $this->escrow->hold($payment);

        // Split: freelancer 600.000, company 400.000 (freelancer_receive = 1.000.000)
        $this->assertTrue($this->escrow->partialRelease($payment, 600000.00, 400000.00));

        $payment->refresh();
        $this->assertSame('released_partial', $payment->funds_status);
        $this->assertSame('600000.00', $payment->released_amount);
        $this->assertSame('400000.00', $payment->refunded_amount);

        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_ESCROW_RELEASED,
            'amount' => '600000.00',
            'user_id' => $payment->freelancer_id,
        ]);
        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_REFUND_ISSUED,
            'amount' => '400000.00',
            'user_id' => $payment->company_id,
        ]);
    }

    public function test_partial_split_rejects_when_total_does_not_match(): void
    {
        $this->expectException(\RuntimeException::class);

        $payment = $this->createPaidPayment();
        $this->escrow->hold($payment);
        $this->escrow->partialRelease($payment, 600000.00, 100000.00); // 700k != 1.000k (nilai pekerjaan)
    }

    public function test_dispute_marks_funds_disputed_without_moving_money(): void
    {
        $payment = $this->createPaidPayment();
        $this->escrow->hold($payment);

        $this->assertTrue($this->escrow->dispute($payment, 'Report #1'));

        $payment->refresh();
        $this->assertSame('disputed', $payment->funds_status);
        $this->assertSame('Report #1', $payment->dispute_reference);

        // Tidak ada pemindahan dana sama sekali
        $this->assertSame(0, WalletLedger::where('payment_id', $payment->id)
            ->whereIn('type', [WalletLedger::TYPE_ESCROW_RELEASED, WalletLedger::TYPE_REFUND_ISSUED])
            ->count());
    }

    public function test_release_rejects_when_funds_not_held(): void
    {
        $this->expectException(\RuntimeException::class);

        $payment = $this->createPaidPayment(); // belum pernah hold
        $this->escrow->release($payment);
    }

    public function test_midtrans_webhook_settlement_marks_funds_held_and_creates_ledger(): void
    {
        $company = User::factory()->create(['role' => 'company']);
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $project = Project::factory()->create(['user_id' => $company->id]);
        $workspace = Workspace::create([
            'project_id' => $project->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            // Catatan: di lingkungan test SQLite, ENUM status workspace tidak dapat
            // diperbarui (migrasi ALTER ENUM bersifat MySQL-only), sehingga memakai
            // nilai yang valid pada ENUM awal. Logika webhook tidak bergantung pada
            // status awal workspace ini.
            'status' => 'Sedang Dikerjakan',
        ]);
        $payment = Payment::create([
            'workspace_id' => $workspace->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            'invoice_number' => 'INV-WEBHOOK-' . uniqid(),
            'amount' => 157500.00,
            'platform_fee' => 7500.00,
            'platform_fee_rate' => 5.00,
            'freelancer_receive' => 150000.00,
            'status' => 'pending',
        ]);

        $orderId = $payment->invoice_number . '_abc123';
        $signature = hash('sha512', $orderId . '200' . '157500.00' . 'SB-Mid-server-TESTKEY123');

        $this->postJson('/api/midtrans/notification', [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => '157500.00',
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'transaction_id' => 'TX-' . uniqid(),
        ])->assertStatus(200);

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertSame('held', $payment->funds_status);
        $this->assertNotNull($payment->held_at);

        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_ESCROW_HELD,
            'amount' => '157500.00',
        ]);

        // Workspace ter-unlock ke Sedang Dikerjakan
        $this->assertSame('Sedang Dikerjakan', $workspace->fresh()->status);
    }

    /**
     * REVISI #5 (R-3) — payment LAMA yang sudah `paid` tetapi funds_status masih
     * `not_applicable` (dibuat/terverifikasi sebelum fitur escrow) harus tetap dapat
     * dirilis saat company menerima hasil pekerjaan: accept() MENAHAN dana lebih dulu
     * (hold) lalu merilisnya (release), sehingga dana tidak terjebak tanpa jalur rilis.
     */
    public function test_company_accept_work_holds_then_releases_legacy_paid_payment(): void
    {
        $company = User::factory()->create(['role' => 'company']);

        // Syarat middleware ensureCompanyAdminOrAbort (grup route company.*).
        CompanyAccountRequest::create([
            'company_name'    => $company->name,
            'contact_person'  => $company->name,
            'company_email'   => $company->email,
            'company_phone'   => '081234567890',
            'company_address' => 'Alamat Perusahaan',
            'request_status'  => 'disetujui',
        ]);

        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $project = Project::factory()->create(['user_id' => $company->id]);

        $workspace = Workspace::create([
            'project_id' => $project->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            'status' => 'Menunggu Review',
        ]);

        // Payment lama: lunas, tetapi belum pernah ditahan (funds_status default not_applicable).
        $payment = Payment::create([
            'workspace_id' => $workspace->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            'invoice_number' => 'INV-LEGACY-' . uniqid(),
            'amount' => 1050000.00,
            'platform_fee' => 50000.00,
            'platform_fee_rate' => 5.00,
            'freelancer_receive' => 1000000.00,
            'status' => 'paid',
            'verified_at' => now(),
        ]);

        // Nilai default kolom: not_applicable (payment lama belum pernah ditahan).
        $this->assertSame(Payment::FUNDS_NOT_APPLICABLE, $payment->fresh()->funds_status);

        $submission = ProjectSubmission::create([
            'workspace_id' => $workspace->id,
            'submitted_by' => $freelancer->id,
            'title' => 'Hasil Pekerjaan',
            'status' => 'pending',
        ]);

        $this->actingAs($company)
            ->post(route('company.workspaces.submissions.accept', [
                'workspace' => $workspace->id,
                'submission' => $submission->id,
            ]))
            ->assertRedirect(route('company.workspaces.show', $workspace));

        // Workspace & submission selesai.
        $this->assertSame('Selesai', $workspace->fresh()->status);
        $this->assertSame('accepted', $submission->fresh()->status);

        // Dana TIDAK terjebak: released + nominal freelancer penuh.
        $payment->refresh();
        $this->assertSame(Payment::FUNDS_RELEASED, $payment->funds_status);
        $this->assertSame('1000000.00', $payment->released_amount);

        // Ledger lengkap & tidak dobel: hold (debit company) → release (credit freelancer) → fee platform.
        $this->assertSame(1, WalletLedger::where('payment_id', $payment->id)
            ->where('type', WalletLedger::TYPE_ESCROW_HELD)
            ->count());
        $this->assertSame(1, WalletLedger::where('payment_id', $payment->id)
            ->where('type', WalletLedger::TYPE_ESCROW_RELEASED)
            ->count());
        $this->assertSame(1, WalletLedger::where('payment_id', $payment->id)
            ->where('type', WalletLedger::TYPE_FEE_EARNED)
            ->count());

        $released = WalletLedger::where('payment_id', $payment->id)
            ->where('type', WalletLedger::TYPE_ESCROW_RELEASED)
            ->first();
        $this->assertEquals(1000000.0, (float) $released->amount);
        $this->assertEquals($freelancer->id, $released->user_id);

        $fee = WalletLedger::where('payment_id', $payment->id)
            ->where('type', WalletLedger::TYPE_FEE_EARNED)
            ->first();
        $this->assertEquals(50000.0, (float) $fee->amount);
        $this->assertNull($fee->user_id);

        // Idempotensi R-3: accept kedua (submission sudah accepted) tidak boleh
        // menghasilkan hold/release/ledger tambahan dan tidak mengubah funds_status.
        $this->actingAs($company)
            ->post(route('company.workspaces.submissions.accept', [
                'workspace' => $workspace->id,
                'submission' => $submission->id,
            ]))
            ->assertRedirect(route('company.workspaces.show', $workspace))
            ->assertSessionHas('error');

        // Tepat 3 baris ledger: 1 hold + 1 release + 1 fee (tidak ada duplikasi).
        $this->assertSame(3, WalletLedger::where('payment_id', $payment->id)->count());

        $payment->refresh();
        $this->assertSame(Payment::FUNDS_RELEASED, $payment->funds_status);
        $this->assertSame('1000000.00', $payment->released_amount);
        $this->assertSame('Selesai', $workspace->fresh()->status);
    }

    /**
     * REVISI #5 (Hardening A) — payment lama `paid + not_applicable` dengan
     * freelancer_receive <= 0 (mis. legacy fee rate 100%) TIDAK boleh diproses otomatis.
     * accept() harus menolak lebih awal: tanpa hold/release/ledger dan tanpa mengubah
     * submission/workspace, karena nominal penerimaan freelancer tidak valid dan
     * pembayaran perlu ditangani melalui resolusi admin.
     */
    public function test_company_accept_work_rejects_legacy_paid_payment_with_zero_freelancer_receive(): void
    {
        $company = User::factory()->create(['role' => 'company']);

        // Syarat middleware ensureCompanyAdminOrAbort (grup route company.*).
        CompanyAccountRequest::create([
            'company_name'    => $company->name,
            'contact_person'  => $company->name,
            'company_email'   => $company->email,
            'company_phone'   => '081234567890',
            'company_address' => 'Alamat Perusahaan',
            'request_status'  => 'disetujui',
        ]);

        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $project = Project::factory()->create(['user_id' => $company->id]);

        $workspace = Workspace::create([
            'project_id' => $project->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            'status' => 'Menunggu Review',
        ]);

        // Legacy fee rate 100%: invariant tetap terjaga (receive 0 + fee 1.000.000 = amount),
        // sehingga data ini benar-benar merepresentasikan baris lama yang bermasalah.
        $payment = Payment::create([
            'workspace_id' => $workspace->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            'invoice_number' => 'INV-LEGACY-ZERO-' . uniqid(),
            'amount' => 1000000.00,
            'platform_fee' => 1000000.00,
            'platform_fee_rate' => 100.00,
            'freelancer_receive' => 0.00,
            'status' => 'paid',
            'verified_at' => now(),
        ]);

        $this->assertSame(Payment::FUNDS_NOT_APPLICABLE, $payment->fresh()->funds_status);

        $submission = ProjectSubmission::create([
            'workspace_id' => $workspace->id,
            'submitted_by' => $freelancer->id,
            'title' => 'Hasil Pekerjaan',
            'status' => 'pending',
        ]);

        $this->actingAs($company)
            ->post(route('company.workspaces.submissions.accept', [
                'workspace' => $workspace->id,
                'submission' => $submission->id,
            ]))
            ->assertRedirect(route('company.workspaces.show', $workspace))
            ->assertSessionHas('error');

        // Ditolak lebih awal: submission & workspace TIDAK berubah.
        $this->assertSame('pending', $submission->fresh()->status);
        $this->assertSame('Menunggu Review', $workspace->fresh()->status);

        // Payment tetap apa adanya: tidak ditahan, tidak dirilis, tidak direfund.
        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertSame(Payment::FUNDS_NOT_APPLICABLE, $payment->funds_status);
        $this->assertNull($payment->held_at);
        $this->assertNull($payment->released_at);
        $this->assertEquals(0.0, (float) $payment->released_amount);
        $this->assertEquals(0.0, (float) $payment->refunded_amount);

        // Tidak ada ledger baru dan tidak ada progress history penyelesaian.
        $this->assertSame(0, WalletLedger::count());
        $this->assertSame(0, \App\Models\ProgressHistory::query()
            ->where('workspace_id', $workspace->id)
            ->count());
    }
}

