<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use App\Models\WalletLedger;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CompanyPaymentFlowTest — TEST 1 dari spesifikasi revisi:
 *   - Webhook Midtrans (settlement) → payment PAID.
 *   - Dana otomatis ditahan escrow (wallet_ledger escrow_held).
 *   - Double webhook TIDAK membuat ledger/escrow ganda.
 */
class CompanyPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.midtrans.server_key' => 'SB-Mid-server-TESTKEY123']);
    }

    private function createPendingPayment(float $amount = 1000000.00): array
    {
        $company    = User::factory()->create(['role' => 'company']);
        $freelancer = User::factory()->create(['role' => 'freelancer']);

        $project = Project::factory()->create(['user_id' => $company->id]);

        $workspace = Workspace::create([
            'project_id'    => $project->id,
            'company_id'    => $company->id,
            'freelancer_id' => $freelancer->id,
            // Catatan: di SQLite, ENUM status workspace hanya nilai migration asli.
            'status'        => 'Sedang Dikerjakan',
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
            'freelancer_receive' => $freelancerReceive,
            'status'             => 'pending',
        ]);

        return [$payment, $workspace];
    }

    public function test_webhook_settlement_marks_payment_paid_and_holds_escrow(): void
    {
        [$payment, $workspace] = $this->createPendingPayment(1000000.00);

        $signature = hash(
            'sha512',
            $payment->invoice_number . '200' . '1000000.00' . 'SB-Mid-server-TESTKEY123'
        );

        $response = $this->postJson('/api/midtrans/notification', [
            'order_id'           => $payment->invoice_number,
            'status_code'        => '200',
            'gross_amount'       => '1000000.00',
            'signature_key'      => $signature,
            'transaction_status' => 'settlement',
            'payment_type'       => 'qris',
        ]);

        $response->assertStatus(200);

        $payment->refresh();
        $this->assertEquals('paid', $payment->status);
        $this->assertNotNull($payment->verified_at);

        // Dana DITAHAN escrow — belum jadi pendapatan freelancer.
        $this->assertEquals(Payment::FUNDS_HELD, $payment->funds_status);

        // Ledger: tepat satu escrow_held untuk company.
        $this->assertEquals(1, WalletLedger::where('type', WalletLedger::TYPE_ESCROW_HELD)->count());
        $held = WalletLedger::where('type', WalletLedger::TYPE_ESCROW_HELD)->first();
        $this->assertEquals('debit', $held->direction);
        $this->assertEquals(1000000.0, (float) $held->amount);
        $this->assertEquals($payment->company_id, $held->user_id);

        // Belum ada dana dirilis / fee dicatat sebelum pekerjaan selesai.
        $this->assertEquals(0, WalletLedger::where('type', WalletLedger::TYPE_ESCROW_RELEASED)->count());
        $this->assertEquals(0, WalletLedger::where('type', WalletLedger::TYPE_FEE_EARNED)->count());
    }

    public function test_double_webhook_does_not_create_duplicate_escrow_ledger(): void
    {
        [$payment, ] = $this->createPendingPayment(500000.00);

        $payload = [
            'order_id'           => $payment->invoice_number,
            'status_code'        => '200',
            'gross_amount'       => '500000.00',
            'transaction_status' => 'settlement',
        ];
        $payload['signature_key'] = hash(
            'sha512',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . 'SB-Mid-server-TESTKEY123'
        );

        // Webhook pertama dan kedua (duplicate dari Midtrans).
        $this->postJson('/api/midtrans/notification', $payload)->assertStatus(200);
        $this->postJson('/api/midtrans/notification', $payload)->assertStatus(200);

        $payment->refresh();
        $this->assertEquals('paid', $payment->status);

        // Tetap SATU escrow held — tidak ada duplikasi.
        $this->assertEquals(1, WalletLedger::where('type', WalletLedger::TYPE_ESCROW_HELD)->count());

        // Escrow adalah debit sisi company — bukan income platform.
        $this->assertEquals(0, WalletLedger::whereNull('user_id')->count());
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        [$payment, ] = $this->createPendingPayment(250000.00);

        $response = $this->postJson('/api/midtrans/notification', [
            'order_id'           => $payment->invoice_number,
            'status_code'        => '200',
            'gross_amount'       => '250000.00',
            'signature_key'      => 'signature-palsu',
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(403);

        // Payment tidak berubah.
        $this->assertEquals('pending', $payment->fresh()->status);
        $this->assertEquals(0, WalletLedger::count());
    }

    public function test_webhook_gross_amount_must_match_database_amount(): void
    {
        [$payment, ] = $this->createPendingPayment(1000000.00);

        // Attacker coba konfirmasi dengan nominal lebih kecil dari tagihan.
        $gross = '10000.00';
        $signature = hash('sha512', $payment->invoice_number . '200' . $gross . 'SB-Mid-server-TESTKEY123');

        $response = $this->postJson('/api/midtrans/notification', [
            'order_id'           => $payment->invoice_number,
            'status_code'        => '200',
            'gross_amount'       => $gross,
            'signature_key'      => $signature,
            'transaction_status' => 'settlement',
        ]);

        // Nominal tidak sesuai database → ditolak (bukan jadi paid).
        $response->assertStatus(400);
        $this->assertEquals('pending', $payment->fresh()->status);
        $this->assertEquals(0, WalletLedger::count());
    }
}