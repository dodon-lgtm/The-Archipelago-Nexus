<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Project;
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

        $freelancerReceive = round($amount * 0.95, 2);
        $fee = round($amount - $freelancerReceive, 2);

        return Payment::create([
            'workspace_id' => $workspace->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            'invoice_number' => 'INV-ESCROW-' . uniqid(),
            'amount' => $amount,
            'platform_fee' => $fee,
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
            'amount' => '1000000.00',
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
        $this->assertSame('950000.00', $payment->released_amount);
        $this->assertNotNull($payment->released_at);

        // Ledger: escrow_released untuk freelancer + fee_earned untuk platform
        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_ESCROW_RELEASED,
            'amount' => '950000.00',
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
        $this->assertSame('1000000.00', $payment->refunded_amount);

        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_REFUND_ISSUED,
            'amount' => '1000000.00',
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

        // Split: freelancer 600.000, company 350.000 (freelancer_receive = 950.000)
        $this->assertTrue($this->escrow->partialRelease($payment, 600000.00, 350000.00));

        $payment->refresh();
        $this->assertSame('released_partial', $payment->funds_status);
        $this->assertSame('600000.00', $payment->released_amount);
        $this->assertSame('350000.00', $payment->refunded_amount);

        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_ESCROW_RELEASED,
            'amount' => '600000.00',
            'user_id' => $payment->freelancer_id,
        ]);
        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_REFUND_ISSUED,
            'amount' => '350000.00',
            'user_id' => $payment->company_id,
        ]);
    }

    public function test_partial_split_rejects_when_total_does_not_match(): void
    {
        $this->expectException(\RuntimeException::class);

        $payment = $this->createPaidPayment();
        $this->escrow->hold($payment);
        $this->escrow->partialRelease($payment, 600000.00, 100000.00); // 700k != 950k
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
            'amount' => 150000.00,
            'platform_fee' => 7500.00,
            'freelancer_receive' => 142500.00,
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
            'transaction_id' => 'TX-' . uniqid(),
        ])->assertStatus(200);

        $payment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertSame('held', $payment->funds_status);
        $this->assertNotNull($payment->held_at);

        $this->assertDatabaseHas('wallet_ledger', [
            'payment_id' => $payment->id,
            'type' => WalletLedger::TYPE_ESCROW_HELD,
            'amount' => '150000.00',
        ]);

        // Workspace ter-unlock ke Sedang Dikerjakan
        $this->assertSame('Sedang Dikerjakan', $workspace->fresh()->status);
    }
}

