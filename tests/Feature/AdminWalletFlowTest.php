<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use App\Models\WalletLedger;
use App\Services\AdminWalletService;
use App\Services\WithdrawalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * AdminWalletFlowTest — TEST 2 s.d. TEST 6 dari spesifikasi revisi:
 *   - Quota income masuk Admin Wallet & tidak dobel.
 *   - Fee withdrawal freelancer 5% benar, masuk Admin Wallet, tidak dobel.
 *   - Expense menjadi debit & saldo berkurang.
 *   - Admin withdrawal: tanpa fee aplikasi, debit tunggal, saldo berkurang,
 *     ditolak bila melebihi saldo.
 *   - Double submit / pemanggilan ulang tidak membuat ledger ganda.
 */
class AdminWalletFlowTest extends TestCase
{
    use RefreshDatabase;

    private function seedPlatformBalance(float $amount, string $invoice): Payment
    {
        $company = User::factory()->create(['role' => 'company']);

        $payment = Payment::create([
            'company_id'     => $company->id,
            'invoice_number' => $invoice,
            'amount'         => $amount,
            'payment_type'   => Payment::PAYMENT_TYPE_QUOTA,
            'status'         => 'paid',
            'verified_at'    => now(),
        ]);

        AdminWalletService::recordProjectQuotaIncome($payment);

        return $payment;
    }

    // ────────────────────────────────────────────────────────────
    // TEST 2 — QUOTA: income Rp10.000 masuk Admin Wallet, sekali saja
    // ────────────────────────────────────────────────────────────
    public function test_quota_income_recorded_once_and_idempotent(): void
    {
        $company = User::factory()->create(['role' => 'company']);

        $payment = Payment::create([
            'company_id'     => $company->id,
            'freelancer_id'  => null,
            'workspace_id'   => null,
            'invoice_number' => 'INV-QOT-20260823-0001',
            'amount'         => AdminWalletService::QUOTA_PRICE, // 10000 — server-side
            'payment_type'   => Payment::PAYMENT_TYPE_QUOTA,
            'status'         => 'paid',
            'verified_at'    => now(),
        ]);

        // Panggil dua kali (simulasi webhook + admin verify bertumpuk).
        AdminWalletService::recordProjectQuotaIncome($payment);
        AdminWalletService::recordProjectQuotaIncome($payment);

        $this->assertEquals(1, WalletLedger::where('type', WalletLedger::TYPE_PROJECT_QUOTA_FEE)->count());
        $this->assertEquals(10000.0, AdminWalletService::balance());

        $ledger = WalletLedger::where('type', WalletLedger::TYPE_PROJECT_QUOTA_FEE)->first();
        $this->assertNull($ledger->user_id);           // selalu milik platform
        $this->assertEquals('credit', $ledger->direction);
        $this->assertEquals('INV-QOT-20260823-0001', $ledger->display_code);
    }

    // ────────────────────────────────────────────────────────────
    // TEST 3 — FREELANCER WITHDRAWAL: fee 5% benar & tidak dobel
    // ────────────────────────────────────────────────────────────
    public function test_freelancer_withdrawal_fee_is_5_percent_and_recorded_once(): void
    {
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $company    = User::factory()->create(['role' => 'company']);

        $project = \App\Models\Project::factory()->create(['user_id' => $company->id]);
        $workspace = \App\Models\Workspace::create([
            'project_id'    => $project->id,
            'company_id'    => $company->id,
            'freelancer_id' => $freelancer->id,
            'status'        => 'Sedang Dikerjakan',
        ]);

        // Freelancer punya pendapatan paid Rp 100.000 (fee platform di luar nilai ini).
        Payment::create([
            'workspace_id'       => $workspace->id,
            'company_id'         => $company->id,
            'freelancer_id'      => $freelancer->id,
            'invoice_number'     => 'INV-TEST-WD-0001',
            'amount'             => 105263.16,
            'platform_fee'       => 5263.16,
            'freelancer_receive' => 100000.00,
            'status'             => 'paid',
            'verified_at'        => now(),
        ]);

        /** @var WithdrawalService $service */
        $service = app(WithdrawalService::class);

        $withdrawal = $service->store([
            'amount'         => 100000.0,
            'method'          => 'bank',
            'bank_name'       => 'BCA',
            'account_name'    => 'Freelancer Test',
            'account_number'  => '1234567890',
        ], $freelancer->id);

        // Matematika fee 5% (server-side).
        $this->assertEquals(5000.0, (float) $withdrawal->fee);
        $this->assertEquals(95000.0, (float) $withdrawal->net_amount);

        // Fee masuk Admin Wallet tepat satu kali.
        $this->assertEquals(1, WalletLedger::where('type', WalletLedger::TYPE_WITHDRAWAL_FEE)->count());
        $this->assertEquals(5000.0, AdminWalletService::balance());

        // Catatan: store() bersifat simulasi instant-success (status "berhasil"
        // sejak awal), sehingga pemanggilan approve() lagi tidak berlaku.
        // Idempotensi fee diuji dengan memanggil pencatat fee sekali lagi:
        AdminWalletService::recordWithdrawalFee($withdrawal);
        $this->assertEquals(1, WalletLedger::where('type', WalletLedger::TYPE_WITHDRAWAL_FEE)->count());
        $this->assertEquals(5000.0, AdminWalletService::balance());

        // Kode tampilan memakai withdrawal_code existing.
        $ledger = WalletLedger::where('type', WalletLedger::TYPE_WITHDRAWAL_FEE)->first();
        $this->assertEquals($withdrawal->withdrawal_code, $ledger->display_code);

        // Saldo tersedia freelancer kini nol.
        $this->assertEquals(0.0, $service->availableBalance($freelancer->id));
    }

    public function test_freelancer_withdrawal_rejects_amount_over_balance(): void
    {
        $freelancer = User::factory()->create(['role' => 'freelancer']);

        /** @var WithdrawalService $service */
        $service = app(WithdrawalService::class);

        $this->expectException(ValidationException::class);

        $service->store([
            'amount'         => 1000.0,
            'method'         => 'bank',
            'bank_name'      => 'BCA',
            'account_name'   => 'Freelancer Test',
            'account_number' => '1234567890',
        ], $freelancer->id);
    }

    // ────────────────────────────────────────────────────────────
    // TEST 4 — ADMIN EXPENSE: debit, saldo berkurang
    // ────────────────────────────────────────────────────────────
    public function test_expense_creates_debit_and_reduces_balance(): void
    {
        // Seed saldo platform Rp 1.000.000 via income kuota.
        $this->seedPlatformBalance(1000000.0, 'INV-QOT-SEED-0001');

        // Catat expense Rp 100.000 (dengan meta kategori — fitur baru).
        $expense = AdminWalletService::recordExpense(
            amount: 100000.0,
            description: 'Pembayaran hosting',
            createdBy: null,
            meta: ['category' => 'operasional', 'category_label' => 'Biaya Operasional'],
        );

        $this->assertNotNull($expense);
        $this->assertEquals('debit', $expense->direction);
        $this->assertNull($expense->user_id);
        $this->assertEquals(900000.0, AdminWalletService::balance());
        $this->assertEquals(900000.0, (float) $expense->balance_after);
        $this->assertEquals('operasional', $expense->meta['category']);
        $this->assertStringStartsWith('EXP-', $expense->display_code);
    }

    // ────────────────────────────────────────────────────────────
    // TEST 5 — ADMIN WITHDRAWAL: tanpa platform fee 5%, hanya fee
    // PROVIDER dari config; debit wallet = nominal PENUH.
    // ────────────────────────────────────────────────────────────
    public function test_admin_withdrawal_has_no_platform_fee_and_debits_once(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Saldo awal Rp 1.000.000 (skenario spesifikasi).
        $this->seedPlatformBalance(1000000.0, 'INV-QOT-SEED-0002');

        $result = AdminWalletService::recordAdminWithdrawal(
            amount: 500000.0,
            method: 'bank', // fee provider fixed Rp 6.500 dari config/withdrawal.php
            bankName: 'BCA',
            accountName: 'Admin Platform',
            accountNumber: '9876543210',
            createdBy: $admin->id,
        );

        $this->assertTrue($result['success']);

        /** @var \App\Models\Withdrawal $withdrawal */
        $withdrawal = $result['withdrawal'];

        // Matematika fee PROVIDER (bukan platform fee 5%):
        // debit = 500.000, fee = 6.500, diterima admin = 493.500.
        $this->assertEquals(6500.0, (float) $withdrawal->fee);
        $this->assertEquals(500000.0, (float) $withdrawal->amount);
        $this->assertEquals(493500.0, (float) $withdrawal->net_amount);
        $this->assertEquals(493500.0, $result['received']);

        // Debit tunggal terikat withdrawal_id, milik platform.
        $ledger = WalletLedger::where('type', WalletLedger::TYPE_ADMIN_WITHDRAWAL)->first();
        $this->assertNotNull($ledger);
        $this->assertEquals($withdrawal->id, $ledger->withdrawal_id);
        $this->assertEquals('debit', $ledger->direction);
        $this->assertEquals(500000.0, (float) $ledger->amount); // debit NOMINAL PENUH
        $this->assertNull($ledger->user_id);

        // Fee provider BUKAN platform income → tidak ada ledger withdrawal_fee.
        $this->assertEquals(0, WalletLedger::where('type', WalletLedger::TYPE_WITHDRAWAL_FEE)->count());

        // Saldo: 1.000.000 − 500.000 (debit penuh) = 500.000.
        $this->assertEquals(500000.0, AdminWalletService::balance());

        // Meta terstruktur untuk riwayat + kode WD-ADMIN-XXXXX.
        $this->assertEquals('bank', $ledger->meta['method']);
        $this->assertEquals(6500.0, $ledger->meta['provider_fee']);
        $this->assertStringStartsWith('WD-ADMIN-', $withdrawal->withdrawal_code);

        // Nomor rekening termasking untuk tampilan history.
        $this->assertSame('******3210', $withdrawal->masked_account_number);

        // Riwayat penarikan admin terbaca dari tabel withdrawals (type=admin).
        $history = AdminWalletService::adminWithdrawalHistory();
        $this->assertEquals(1, $history->total());
    }

    public function test_admin_withdrawal_rejected_when_over_balance_and_ledger_unchanged(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Saldo hanya Rp 300.000, penarikan Rp 500.000 → harus ditolak.
        $this->seedPlatformBalance(300000.0, 'INV-QOT-SEED-0005');

        $before = [
            'count'   => WalletLedger::whereNull('user_id')->count(),
            'balance' => AdminWalletService::balance(),
        ];

        $result = AdminWalletService::recordAdminWithdrawal(
            amount: 500000.0,
            method: 'bank',
            bankName: 'BCA',
            accountName: 'Admin Platform',
            accountNumber: '9876543210',
            createdBy: $admin->id,
        );

        $this->assertFalse($result['success']);
        $this->assertNull($result['withdrawal']);

        // Ledger TIDAK berubah sama sekali (tidak ada withdrawal & debit baru).
        $this->assertEquals($before['count'], WalletLedger::whereNull('user_id')->count());
        $this->assertEquals($before['balance'], AdminWalletService::balance());
        $this->assertEquals(0, \App\Models\Withdrawal::ofType(\App\Models\Withdrawal::TYPE_ADMIN)->count());
    }

    public function test_admin_withdrawal_ewallet_fee_is_percent_based(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Saldo Rp 200.000 (fee ewallet = 1% → 2.000, diterima 198.000).
        $this->seedPlatformBalance(100000.0, 'INV-QOT-SEED-0006');
        $this->seedPlatformBalance(100000.0, 'INV-QOT-SEED-0007');

        $result = AdminWalletService::recordAdminWithdrawal(
            amount: 200000.0,
            method: 'ewallet',
            bankName: 'OVO',
            accountName: 'Admin Platform',
            accountNumber: '08123456789',
            createdBy: $admin->id,
        );

        $this->assertTrue($result['success']);
        $this->assertEquals(2000.0, $result['fee']);
        $this->assertEquals(198000.0, $result['received']);
    }

    // ────────────────────────────────────────────────────────────
    // TEST 6 — DOUBLE SUBMIT via HTTP (one-time token _tx)
    // ────────────────────────────────────────────────────────────
    public function test_double_submit_admin_withdrawal_is_blocked_by_tx_token(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Seed saldo Rp 1.000.000.
        $this->seedPlatformBalance(1000000.0, 'INV-QOT-SEED-0003');

        // Buka halaman wallet → token _tx diterbitkan di kedua form.
        $response = $this->actingAs($admin)->get(route('admin.wallet.index'));
        $response->assertOk();

        $html = $response->getContent();
        preg_match_all('/name="_tx" value="([a-zA-Z0-9]{40})"/', $html, $matches);
        $this->assertCount(2, $matches[1], 'Dua form (expense & withdraw) harus punya token _tx.');
        $withdrawToken = $matches[1][1]; // token kedua = form withdraw

        $payload = [
            '_tx'            => $withdrawToken,
            'amount'         => '300.000',       // format Rupiah dari frontend
            'method'         => 'bank',
            'bank_name'      => 'BCA',
            'account_name'   => 'Admin Platform',
            'account_number' => '9876543210',
        ];

        // Submit pertama → sukses (redirect PRG).
        $first = $this->actingAs($admin)
            ->from(route('admin.wallet.index'))
            ->post(route('admin.wallet.withdraw'), $payload);
        $first->assertRedirect(route('admin.wallet.index'));
        $first->assertSessionHas('success');

        // Submit KEDUA dengan token yang sama (double click / refresh resubmit) → ditolak.
        $second = $this->actingAs($admin)
            ->from(route('admin.wallet.index'))
            ->post(route('admin.wallet.withdraw'), $payload);
        $second->assertRedirect(route('admin.wallet.index'));
        $second->assertSessionHas('error');

        // Hanya SATU debit yang tercipta.
        $this->assertEquals(
            1,
            WalletLedger::whereNull('user_id')->where('type', WalletLedger::TYPE_ADMIN_WITHDRAWAL)->count()
        );
        $this->assertEquals(700000.0, AdminWalletService::balance());
    }

    public function test_double_submit_expense_is_blocked_by_tx_token(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->seedPlatformBalance(1000000.0, 'INV-QOT-SEED-0004');

        $response = $this->actingAs($admin)->get(route('admin.wallet.index'));
        $html = $response->getContent();
        preg_match_all('/name="_tx" value="([a-zA-Z0-9]{40})"/', $html, $matches);
        $expenseToken = $matches[1][0]; // token pertama = form expense

        $payload = [
            '_tx'          => $expenseToken,
            'amount'       => 100000,
            'description'  => 'Pembayaran hosting',
            'category'     => 'operasional',
            'expense_date' => now()->toDateString(),
        ];

        $first = $this->actingAs($admin)
            ->post(route('admin.wallet.expense'), $payload);
        $first->assertRedirect(route('admin.wallet.index'));

        $second = $this->actingAs($admin)
            ->post(route('admin.wallet.expense'), $payload);
        $second->assertRedirect(route('admin.wallet.index'));
        $second->assertSessionHas('error');

        $this->assertEquals(1, WalletLedger::where('type', WalletLedger::TYPE_ADMIN_EXPENSE)->count());
        $this->assertEquals(900000.0, AdminWalletService::balance());
    }

    public function test_wallet_statistics_are_computed_from_ledger(): void
    {
        $this->assertEquals(0.0, AdminWalletService::balance());
        $this->assertEquals(0.0, AdminWalletService::totalIncome());
        $this->assertEquals(0.0, AdminWalletService::totalExpense());
        $this->assertEquals(0.0, AdminWalletService::monthlyIncome());
        $this->assertEquals(0.0, AdminWalletService::monthlyExpense());
    }
}
