<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Workspace;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.midtrans.server_key' => 'SB-Mid-server-TESTKEY123']);
    }

    private function createDummyPayment(float $amount = 150000.00, string $status = 'pending'): Payment
    {
        $company = User::factory()->create(['role' => 'company']);
        $freelancer = User::factory()->create(['role' => 'freelancer']);
                $project = Project::factory()->create(['user_id' => $company->id]);
                $workspace = Workspace::create([
            'project_id' => $project->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            // Catatan: di SQLite (test environment), ENUM project_workspaces.status hanya
            // mencakup nilai asli migration (Sedang Dikerjakan, Menunggu Revisi, Selesai).
            // Nilai 'Menunggu Pembayaran' hanya tersedia di MySQL. Pakai 'Sedang Dikerjakan'
            // untuk test agar tidak bergantung pada ALTER TABLE ENUM (MySQL-only).
            'status' => 'Sedang Dikerjakan',
        ]);
        return Payment::create([
            'workspace_id' => $workspace->id,
            'company_id' => $company->id,
            'freelancer_id' => $freelancer->id,
            'invoice_number' => 'INV-TEST-' . uniqid(),
            'amount' => $amount,
            'status' => $status,
        ]);
    }

    public function test_settlement_updates_payment_to_paid(): void
    {
        $payment = $this->createDummyPayment(150000.00);
        $signature = hash('sha512', $payment->invoice_number . '200' . '150000.00' . 'SB-Mid-server-TESTKEY123');

        $response = $this->postJson('/api/midtrans/notification', [
            'order_id' => $payment->invoice_number,
            'status_code' => '200',
            'gross_amount' => '150000.00',
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'paid']);
    }

    public function test_settlement_with_suffixed_order_id_still_updates_payment_to_paid(): void
    {
        // MidtransService::buildOrderId() menghasilkan order_id unik per attempt,
        // mis. "{invoice_number}_a1b2c3". Webhook harus tetap bisa menemukan Payment-nya.
        $payment = $this->createDummyPayment(150000.00);
        $orderId = $payment->invoice_number . '_a1b2c3';
        $signature = hash('sha512', $orderId . '200' . '150000.00' . 'SB-Mid-server-TESTKEY123');

        $response = $this->postJson('/api/midtrans/notification', [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => '150000.00',
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'paid']);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'midtrans_transaction_id' => null,
        ]);
    }

    public function test_invalid_signature_rejects_notification(): void
    {
        $payment = $this->createDummyPayment(100000.00);
        
        $response = $this->postJson('/api/midtrans/notification', [
            'order_id' => $payment->invoice_number,
            'status_code' => '200',
            'gross_amount' => '100000.00',
            'signature_key' => 'INVALID',
            'transaction_status' => 'settlement',
        ]);

                $response->assertStatus(403);
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'pending']);
    }

    /**
     * Idempotency (H): notifikasi settlement yang sama dikirim dua kali tidak
     * boleh menciptakan Payment/Workspace baru dan tidak boleh merusak status.
     */
    public function test_duplicate_settlement_webhook_is_idempotent(): void
    {
        $payment = $this->createDummyPayment(150000.00);
        $orderId = $payment->invoice_number;
        $signature = hash('sha512', $orderId . '200' . '150000.00' . 'SB-Mid-server-TESTKEY123');

        $payload = [
            'order_id' => $orderId,
            'status_code' => '200',
            'gross_amount' => '150000.00',
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
        ];

        // 1st notification -> payment menjadi paid
        $this->postJson('/api/midtrans/notification', $payload)->assertStatus(200);
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'paid']);

        // 2nd notifikasi identik -> idempotent
        $this->postJson('/api/midtrans/notification', $payload)->assertStatus(200);

        // Status tetap paid, tidak ada rekor Payment/Workspace baru
        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => 'paid']);
        $this->assertSame(1, Payment::where('workspace_id', $payment->workspace_id)->count(), 'Tidak boleh ada Payment baru.');
        $this->assertSame(1, Workspace::where('id', $payment->workspace_id)->count(), 'Tidak boleh ada Workspace baru.');
    }
}
