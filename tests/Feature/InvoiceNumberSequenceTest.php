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
use App\Services\InvoiceNumberService;
use App\Services\MidtransService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * InvoiceNumberSequenceTest — M-2B: alokasi nomor invoice lewat `invoice_sequences`.
 *
 * KETERBATASAN YANG DISADARI (penting):
 * Test ini berjalan di SQLite `:memory:` (phpunit.xml). SQLite TIDAK mendukung
 * `SELECT ... FOR UPDATE` (no-op) dan menserialisasi penulisan, sehingga test ini
 * TIDAK membuktikan row locking MySQL. Yang benar-benar diuji di sini:
 *   - state transition allocator (first-of-day, increment, rollback coupling),
 *   - pemisahan sequence workspace vs quota,
 *   - seeding dari invoice existing (format + scope + tanggal),
 *   - deletion safety (tidak memakai baris payment / max(id)),
 *   - sequence > 9999 tidak terpotong,
 *   - backstop UNIQUE invoice_number + retry maksimal 1x,
 *   - integrasi dua generator di controller.
 * Row locking MySQL (race safety nyata) divalidasi terpisah lewat desain +
 * pemeriksaan schema; bukan oleh test ini.
 */
class InvoiceNumberSequenceTest extends TestCase
{
    use RefreshDatabase;

    /** Tanggal tetap untuk test seeding/format (bukan "hari ini"). */
    private const DAY = '2026-08-28';

    private function day(): Carbon
    {
        return Carbon::parse(self::DAY);
    }

    private function today(): string
    {
        return now()->format('Ymd');
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
            'company_name'    => 'PT Uji Coba',
            'contact_person'  => $company->name,
            'company_email'   => $company->email,
            'company_phone'   => '081234567890',
            'company_address' => 'Jl. Uji Coba No. 1, Jakarta',
            'request_status'  => 'disetujui',
        ]);

        return $company;
    }

    /** Admin menetapkan harga kuota (Financial Settings). */
    private function setUploadPrice(float $price): void
    {
        FinancialSetting::query()->delete();
        FinancialSetting::create([
            'project_fee_rate' => 5,
            'withdrawal_fee_rate' => 5,
            'free_project_uploads_per_month' => 3,
            'paid_project_upload_price' => $price,
        ]);
    }

    /** Jalankan alur nyata "pilih freelancer" dan kembalikan Payment workspace-nya. */
    private function acceptOffer(User $company, float $harga = 1000000): Payment
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

    /**
     * Payment "existing/legacy" langsung ke DB (tanpa generator) untuk menguji
     * seeding & kompatibilitas invoice lama.
     */
    private function existingInvoice(
        string $invoiceNumber,
        string $type = Payment::PAYMENT_TYPE_WORKSPACE,
        ?string $createdDate = null,
        ?User $company = null
    ): Payment {
        $payment = Payment::create([
            'workspace_id'   => null,
            'company_id'     => ($company ?? User::factory()->create(['role' => 'company']))->id,
            'freelancer_id'  => null,
            'invoice_number' => $invoiceNumber,
            'amount'         => 100000.00,
            'payment_type'   => $type,
            'status'         => 'pending',
        ]);

        if ($createdDate !== null) {
            $timestamp = Carbon::parse($createdDate . ' 10:00:00');
            $payment->created_at = $timestamp;
            $payment->updated_at = $timestamp;
            $payment->save();
        }

        return $payment;
    }

    // ───────────────────────── FORMAT & INCREMENT ─────────────────────────

    public function test_first_workspace_invoice_of_a_new_day_is_0001(): void
    {
        $invoice = InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day());

        $this->assertSame('INV-20260828-0001', $invoice);
        $this->assertMatchesRegularExpression('/^INV-\d{8}-\d{4,}$/', $invoice);
    }

    public function test_first_quota_invoice_of_a_new_day_is_qot_0001(): void
    {
        $invoice = InvoiceNumberService::next(InvoiceNumberService::SCOPE_QUOTA, $this->day());

        $this->assertSame('INV-QOT-20260828-0001', $invoice);
        $this->assertMatchesRegularExpression('/^INV-QOT-\d{8}-\d{4,}$/', $invoice);
    }

    public function test_workspace_sequence_increments_sequentially(): void
    {
        $numbers = [];

        for ($i = 0; $i < 3; $i++) {
            $numbers[] = InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day());
        }

        $this->assertSame([
            'INV-20260828-0001',
            'INV-20260828-0002',
            'INV-20260828-0003',
        ], $numbers);
    }

    public function test_workspace_and_quota_sequences_are_independent_on_the_same_date(): void
    {
        $workspace1 = InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day());
        $quota1 = InvoiceNumberService::next(InvoiceNumberService::SCOPE_QUOTA, $this->day());
        $workspace2 = InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day());
        $quota2 = InvoiceNumberService::next(InvoiceNumberService::SCOPE_QUOTA, $this->day());

        $this->assertSame('INV-20260828-0001', $workspace1);
        $this->assertSame('INV-QOT-20260828-0001', $quota1);
        $this->assertSame('INV-20260828-0002', $workspace2);
        $this->assertSame('INV-QOT-20260828-0002', $quota2);
    }

    public function test_sequence_is_stored_per_scope_and_date(): void
    {
        InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day());
        InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day());
        InvoiceNumberService::next(InvoiceNumberService::SCOPE_QUOTA, $this->day());
        InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, Carbon::parse('2026-08-29'));

        $rows = DB::table('invoice_sequences')->get();

        $this->assertCount(3, $rows, 'Satu row per (scope, tanggal) — tidak boleh ada row ganda.');
        $this->assertSame(2, (int) $rows->firstWhere(fn ($r) => $r->scope === 'workspace' && $r->seq_date === self::DAY)->last_number);
        $this->assertSame(1, (int) $rows->firstWhere('scope', 'quota')->last_number);
        $this->assertSame(1, (int) $rows->firstWhere(fn ($r) => $r->seq_date === '2026-08-29')->last_number);
    }

    public function test_invoice_sequences_has_unique_index_on_scope_and_date(): void
    {
        $index = collect(Schema::getIndexes('invoice_sequences'))
            ->firstWhere('name', 'invoice_sequences_scope_date_unique');

        $this->assertNotNull($index, 'UNIQUE(scope, seq_date) tidak ditemukan pada invoice_sequences.');
        $this->assertTrue((bool) $index['unique']);
        $this->assertSame(['scope', 'seq_date'], array_values($index['columns']));
    }

    public function test_allocator_rejects_unknown_scope(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        InvoiceNumberService::next('unknown-scope', $this->day());
    }

    // ───────────────────────── LEBIH DARI 9999 ─────────────────────────

    public function test_sequence_beyond_9999_is_not_truncated(): void
    {
        DB::table('invoice_sequences')->insert([
            'scope' => InvoiceNumberService::SCOPE_WORKSPACE,
            'seq_date' => self::DAY,
            'last_number' => 9999,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertSame('INV-20260828-10000', InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day()));
        $this->assertSame('INV-20260828-10001', InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day()));
    }

    // ───────────────────── SEEDING DARI INVOICE EXISTING ─────────────────────

    public function test_workspace_sequence_is_seeded_from_existing_workspace_invoice(): void
    {
        $this->existingInvoice('INV-20260824-0001', Payment::PAYMENT_TYPE_WORKSPACE, '2026-08-24');
        $this->existingInvoice('INV-20260824-0036', Payment::PAYMENT_TYPE_WORKSPACE, '2026-08-24');

        $invoice = InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, Carbon::parse('2026-08-24'));

        $this->assertSame('INV-20260824-0037', $invoice);
    }

    public function test_quota_sequence_is_seeded_from_existing_quota_invoice(): void
    {
        $this->existingInvoice('INV-QOT-20260902-0043', Payment::PAYMENT_TYPE_QUOTA, '2026-09-02');

        $invoice = InvoiceNumberService::next(InvoiceNumberService::SCOPE_QUOTA, Carbon::parse('2026-09-02'));

        $this->assertSame('INV-QOT-20260902-0044', $invoice);
    }

    public function test_quota_invoice_is_not_used_as_workspace_sequence_source(): void
    {
        $this->existingInvoice('INV-QOT-20260828-0099', Payment::PAYMENT_TYPE_QUOTA, self::DAY);

        $this->assertSame(
            'INV-20260828-0001',
            InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day()),
            'Invoice quota tidak boleh menaikkan sequence workspace (regresi F-03).'
        );

        $this->assertSame('INV-QOT-20260828-0100', InvoiceNumberService::next(InvoiceNumberService::SCOPE_QUOTA, $this->day()));
    }

    public function test_legacy_and_non_conforming_invoices_do_not_poison_sequence(): void
    {
        // Legacy non-conforming (data nyata dari audit) + tail non-numerik.
        $this->existingInvoice('INV-QOT-RENDER-0001', Payment::PAYMENT_TYPE_QUOTA, '2026-08-23');
        $this->existingInvoice('INV-20260828-ABCD', Payment::PAYMENT_TYPE_WORKSPACE, self::DAY);

        $this->assertSame('INV-20260828-0001', InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day()));
        $this->assertSame('INV-QOT-20260828-0001', InvoiceNumberService::next(InvoiceNumberService::SCOPE_QUOTA, $this->day()));
    }

    public function test_seeding_only_considers_invoices_of_the_same_date(): void
    {
        $this->existingInvoice('INV-20260824-0036', Payment::PAYMENT_TYPE_WORKSPACE, '2026-08-24');

        $this->assertSame('INV-20260828-0001', InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day()));
    }

    // ───────────── DELETION SAFETY / TIDAK TERIKAT BARIS PAYMENT ─────────────

    public function test_sequence_is_not_derived_from_payment_rows_or_max_id(): void
    {
        $this->assertSame('INV-20260828-0001', InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day()));
        $this->assertSame('INV-20260828-0002', InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day()));

        // Payment "noise" dengan nomor jauh lebih tinggi muncul setelah sequence berjalan.
        $noise = $this->existingInvoice('INV-20260828-0500', Payment::PAYMENT_TYPE_WORKSPACE, self::DAY);

        // Payment noise adalah baris terakhir menurut id — algoritma lama akan menghasilkan
        // 0501; sequence baru harus tetap lanjut dari state-nya sendiri.
        $this->assertSame('INV-20260828-0500', $noise->invoice_number);
        $this->assertSame($noise->id, Payment::max('id'));
        $this->assertSame('INV-20260828-0003', InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day()));

        // Hapus payment dengan id terbesar → sequence TIDAK mundur / tidak reuse nomor.
        $noise->delete();

        $this->assertSame('INV-20260828-0004', InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day()));
    }

    // ───────────── KETERIKATAN TRANSAKSI (rollback coupling) ─────────────

    public function test_allocation_inside_a_transaction_is_rolled_back_with_it(): void
    {
        DB::beginTransaction();

        $this->assertSame('INV-20260828-0001', InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day()));

        DB::rollBack();

        $this->assertSame(0, DB::table('invoice_sequences')->where('scope', 'workspace')->count());
        // Nomor tidak "bocor": alokasi berikutnya tetap mulai dari 0001.
        $this->assertSame('INV-20260828-0001', InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, $this->day()));
    }

    // ───────────── KOMPATIBILITAS INVOICE EXISTING ─────────────

    public function test_existing_invoices_are_never_modified_by_the_allocator(): void
    {
        $workspaceInvoice = $this->existingInvoice('INV-20260824-0036', Payment::PAYMENT_TYPE_WORKSPACE, '2026-08-24');
        $legacyQuotaInvoice = $this->existingInvoice('INV-QOT-RENDER-0001', Payment::PAYMENT_TYPE_QUOTA, '2026-08-23');

        $fields = ['id', 'invoice_number', 'amount', 'status', 'payment_type', 'workspace_id', 'company_id'];
        $workspaceBefore = $workspaceInvoice->only($fields);
        $legacyBefore = $legacyQuotaInvoice->only($fields);

        InvoiceNumberService::next(InvoiceNumberService::SCOPE_WORKSPACE, Carbon::parse('2026-08-24'));
        InvoiceNumberService::next(InvoiceNumberService::SCOPE_QUOTA, Carbon::parse('2026-08-23'));

        $this->assertSame($workspaceBefore, $workspaceInvoice->fresh()->only($fields));
        $this->assertSame($legacyBefore, $legacyQuotaInvoice->fresh()->only($fields));
        $this->assertSame(2, Payment::count(), 'Tidak boleh ada invoice baru hanya karena alokasi nomor.');
    }

    // ───────────── BACKSTOP UNIQUE invoice_number ─────────────

    public function test_duplicate_invoice_number_is_still_rejected_by_database(): void
    {
        $existing = $this->existingInvoice('INV-20260828-0001', Payment::PAYMENT_TYPE_WORKSPACE, self::DAY);

        $failed = false;

        try {
            $this->existingInvoice('INV-20260828-0001', Payment::PAYMENT_TYPE_WORKSPACE, self::DAY);
        } catch (QueryException $e) {
            $failed = true;
        }

        $this->assertTrue($failed, 'UNIQUE payments.invoice_number harus tetap menolak duplikat.');
        $this->assertSame(1, Payment::where('invoice_number', 'INV-20260828-0001')->count());
        $this->assertSame('INV-20260828-0001', $existing->fresh()->invoice_number);
    }

    public function test_create_with_retry_recovers_from_unexpected_duplicate(): void
    {
        // Paksa kondisi tak terduga: sequence mulai dari 0 tetapi 0001 sudah terpakai.
        DB::table('invoice_sequences')->insert([
            'scope' => InvoiceNumberService::SCOPE_WORKSPACE,
            'seq_date' => self::DAY,
            'last_number' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $company = User::factory()->create(['role' => 'company']);
        $this->existingInvoice('INV-20260828-0001', Payment::PAYMENT_TYPE_WORKSPACE, self::DAY, $company);

        $created = InvoiceNumberService::createWithRetry(
            scope: InvoiceNumberService::SCOPE_WORKSPACE,
            persist: fn (string $invoiceNumber): Payment => $this->existingInvoice(
                $invoiceNumber,
                Payment::PAYMENT_TYPE_WORKSPACE,
                self::DAY,
                $company
            ),
            date: $this->day(),
        );

        $this->assertInstanceOf(Payment::class, $created);
        $this->assertSame('INV-20260828-0002', $created->invoice_number);
        $this->assertSame(2, Payment::where('invoice_number', 'like', 'INV-20260828-%')->count());
    }

    public function test_create_with_retry_throws_clear_exception_when_retry_also_fails(): void
    {
        DB::table('invoice_sequences')->insert([
            'scope' => InvoiceNumberService::SCOPE_WORKSPACE,
            'seq_date' => self::DAY,
            'last_number' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $company = User::factory()->create(['role' => 'company']);
        $this->existingInvoice('INV-20260828-0001', Payment::PAYMENT_TYPE_WORKSPACE, self::DAY, $company);
        $this->existingInvoice('INV-20260828-0002', Payment::PAYMENT_TYPE_WORKSPACE, self::DAY, $company);

        try {
            InvoiceNumberService::createWithRetry(
                scope: InvoiceNumberService::SCOPE_WORKSPACE,
                persist: fn (string $invoiceNumber): Payment => $this->existingInvoice(
                    $invoiceNumber,
                    Payment::PAYMENT_TYPE_WORKSPACE,
                    self::DAY,
                    $company
                ),
                date: $this->day(),
            );

            $this->fail('Exception seharusnya dilempar setelah retry gagal.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('bentrok dua kali', $e->getMessage());
        }

        // Tidak ada invoice baru, tidak ada invoice existing yang dihapus/diubah.
        $this->assertSame(2, Payment::count());
    }

    // ───────────── ALUR CONTROLLER: WORKSPACE (selectFreelancer) ─────────────

    public function test_select_freelancer_generates_workspace_invoice_from_sequence(): void
    {
        $company = $this->completeCompany();

        $payment = $this->acceptOffer($company);

        $this->assertSame('INV-' . $this->today() . '-0001', $payment->invoice_number);
        $this->assertSame(1, DB::table('invoice_sequences')
            ->where('scope', InvoiceNumberService::SCOPE_WORKSPACE)
            ->where('seq_date', now()->toDateString())
            ->count());
    }

    public function test_consecutive_workspace_invoices_increment_in_full_flow(): void
    {
        $company = $this->completeCompany();

        $first = $this->acceptOffer($company);
        $second = $this->acceptOffer($company);

        $this->assertSame('INV-' . $this->today() . '-0001', $first->invoice_number);
        $this->assertSame('INV-' . $this->today() . '-0002', $second->invoice_number);
        $this->assertNotSame($first->invoice_number, $second->invoice_number);
    }

    // ───────────── ALUR CONTROLLER: QUOTA (ensurePendingQuotaPayment) ─────────────

    public function test_quota_invoice_does_not_contaminate_workspace_sequence_end_to_end(): void
    {
        $company = $this->completeCompany();

        // Invoice kuota dibuat lebih dulu pada hari yang sama.
        $quota = PaymentController::ensurePendingQuotaPayment($company->id);
        $this->assertSame('INV-QOT-' . $this->today() . '-0001', $quota->invoice_number);

        // Workspace harus tetap mulai dari 0001 — bukan 0002 (regresi F-03).
        $workspacePayment = $this->acceptOffer($company);
        $this->assertSame('INV-' . $this->today() . '-0001', $workspacePayment->invoice_number);

        // Quota berikutnya (harga berubah) juga tidak terpengaruh sequence workspace.
        $this->setUploadPrice(20000);

        $nextQuota = PaymentController::ensurePendingQuotaPayment($company->id);
        $this->assertSame('INV-QOT-' . $this->today() . '-0002', $nextQuota->invoice_number);
    }

    public function test_ensure_pending_quota_payment_reuses_pending_with_same_price(): void
    {
        $company = $this->completeCompany();

        $first = PaymentController::ensurePendingQuotaPayment($company->id);
        $second = PaymentController::ensurePendingQuotaPayment($company->id);

        $this->assertSame($first->id, $second->id, 'Pending kuota dengan harga sama harus di-reuse.');
        $this->assertSame(1, Payment::where('payment_type', Payment::PAYMENT_TYPE_QUOTA)->count());
        $this->assertSame(1, DB::table('invoice_sequences')->where('scope', InvoiceNumberService::SCOPE_QUOTA)->count());
    }

    public function test_quota_sequence_survives_deleted_payment(): void
    {
        $company = $this->completeCompany();

        $first = PaymentController::ensurePendingQuotaPayment($company->id);
        $this->assertSame('INV-QOT-' . $this->today() . '-0001', $first->invoice_number);

        // Hapus payment (hanya data test) → sequence tetap berjalan, nomor tidak dipakai ulang.
        $first->delete();

        $second = PaymentController::ensurePendingQuotaPayment($company->id);

        $this->assertSame('INV-QOT-' . $this->today() . '-0002', $second->invoice_number);
    }

    public function test_start_quota_payment_route_does_not_duplicate_invoice(): void
    {
        $company = $this->completeCompany();

        // Rute GET tetap dipertahankan (follow-up: GET → POST di luar scope M-2B).
        $this->actingAs($company)->get(route('company.quota.payment.start'))->assertRedirect();
        $this->actingAs($company)->get(route('company.quota.payment.start'))->assertRedirect();

        $payments = Payment::where('company_id', $company->id)
            ->where('payment_type', Payment::PAYMENT_TYPE_QUOTA)
            ->get();

        $this->assertCount(1, $payments, 'Panggilan berulang tidak boleh membuat invoice ganda.');
        $this->assertSame('INV-QOT-' . $this->today() . '-0001', $payments->first()->invoice_number);
    }

    // ───────────── KOMPATIBILITAS MIDTRANS ─────────────

    public function test_generated_invoice_number_is_midtrans_compatible(): void
    {
        $company = $this->completeCompany();
        $payment = PaymentController::ensurePendingQuotaPayment($company->id);

        $this->assertStringNotContainsString('_', $payment->invoice_number);

        $orderId = app(MidtransService::class)->buildOrderId($payment);

        $this->assertStringStartsWith($payment->invoice_number . MidtransService::ORDER_ID_SEPARATOR, $orderId);
        $this->assertSame(
            $payment->invoice_number,
            MidtransService::resolveInvoiceFromOrderId($orderId)
        );
    }
}
