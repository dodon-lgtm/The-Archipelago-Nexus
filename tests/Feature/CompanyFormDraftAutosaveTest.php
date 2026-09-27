<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectSubmission;
use App\Models\Report;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression test auto-save draft form Company
 * (public/js/form-draft-autosave.js + partials/form-draft-autosave.blade.php).
 *
 * Kontrak server-side yang dijaga di sini:
 *  - halaman Company memuat engine draft DENGAN namespace terpisah
 *    (data-draft-namespace="apexforge.co.draft.v1:") dan key berawalan "co:";
 *  - form Company yang relevan (proyek, profil, laporan, ulasan, pembayaran,
 *    workspace) sudah ditandai data-draft-form + data-draft-key yang benar;
 *  - halaman Company tidak pernah memuat key/namespace milik Freelancer
 *    (begitu pula sebaliknya — lihat FreelancerFormDraftAutosaveTest);
 *  - controller mengirim penanda hapus draft (session draft_clear /
 *    draft_clear_prefix) untuk aksi sukses yang redirect-nya kembali ke path
 *    yang sama, karena engine sengaja TIDAK menghapus draft pada kondisi itu.
 *
 * Perilaku engine sendiri (debounce, namespace, pending, prune storage, dst)
 * diuji di tests/js/form-draft-autosave.test.js.
 */
class CompanyFormDraftAutosaveTest extends TestCase
{
    use RefreshDatabase;

    /** Namespace localStorage engine untuk halaman Company. */
    private const CO_NS = 'apexforge.co.draft.v1:';

    /** Namespace localStorage engine untuk halaman Freelancer. */
    private const FL_NS = 'apexforge.fl.draft.v1:';

    private User $company;
    private User $freelancer;
    private Project $project;
    private Workspace $workspace;

    protected function setUp(): void
    {
        parent::setUp();

        $this->freelancer = User::factory()->create(['role' => 'freelancer']);
        $this->company = User::factory()->create(['role' => 'company']);

        // Wajib agar middleware ensureCompanyAdminOrAbort tidak memberi 403.
        CompanyAccountRequest::create([
            'company_name'    => $this->company->name,
            'contact_person'  => $this->company->name,
            'company_email'   => $this->company->email,
            'company_phone'   => '081234567890',
            'company_address' => 'Alamat Perusahaan',
            'request_status'  => 'disetujui',
        ]);

        $this->project = Project::factory()->create([
            'user_id' => $this->company->id,
            'status'  => Project::STATUS_OPEN,
        ]);

        $this->workspace = Workspace::create([
            'project_id'    => $this->project->id,
            'company_id'    => $this->company->id,
            'freelancer_id' => $this->freelancer->id,
            'status'        => 'Sedang Dikerjakan',
        ]);
    }

    private function asCompany(string $routeName, array $params = []): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($this->company)->get(route($routeName, $params));
    }

    /**
     * Assert bahwa halaman Company memuat engine draft dengan namespace Company,
     * dan tidak pernah menyentuh namespace/key milik Freelancer.
     */
    private function assertCompanyDraftPage(\Illuminate\Testing\TestResponse $response): void
    {
        $response->assertOk();
        $response->assertSee('js/form-draft-autosave.js', false);
        $response->assertSee('data-draft-namespace="' . self::CO_NS . '"', false);
        $response->assertDontSee(self::FL_NS, false);
    }

    public function test_halaman_buat_proyek_memuat_engine_draft_company(): void
    {
        $response = $this->asCompany('company.projects.create');

        $this->assertCompanyDraftPage($response);

        $response->assertSee('data-draft-key="co:project-create"', false);
        // Budget tersimpan di hidden #real_budget → wajib data-draft-include
        // (hidden tanpa flag itu sengaja dilewati engine).
        $response->assertSee('name="budget" id="real_budget"', false);
        $response->assertSee('data-draft-include', false);
    }

    public function test_halaman_edit_proyek_memuat_draft_per_project(): void
    {
        $response = $this->asCompany('company.projects.edit', ['project' => $this->project->id]);

        $this->assertCompanyDraftPage($response);

        $response->assertSee('data-draft-key="co:project-edit:' . $this->project->id . '"', false);
    }

    public function test_halaman_edit_profil_company_memuat_draft(): void
    {
        $response = $this->asCompany('company.profile.edit');

        $this->assertCompanyDraftPage($response);

        $response->assertSee('data-draft-key="co:profile:' . $this->company->id . '"', false);
    }

    public function test_halaman_buat_laporan_company_memuat_draft(): void
    {
        $response = $this->asCompany('company.reports.create');

        $this->assertCompanyDraftPage($response);

        // Tanpa konteks (workspace/project/freelancer) → key memakai 0-0-0.
        $response->assertSee('data-draft-key="co:report-create:0-0-0"', false);
    }

    public function test_halaman_review_create_company_memuat_draft_per_project(): void
    {
        $response = $this->asCompany('company.client.review.create', ['project' => $this->project->id]);

        $this->assertCompanyDraftPage($response);

        $response->assertSee('data-draft-key="co:review-create:' . $this->project->id . '"', false);
    }

    public function test_halaman_upload_bukti_pembayaran_memuat_draft_per_payment(): void
    {
        $payment = $this->makeWorkspacePayment();

        $response = $this->asCompany('company.payments.upload-form', ['workspace' => $this->workspace->id]);

        $this->assertCompanyDraftPage($response);

        $response->assertSee('data-draft-key="co:payment-upload:' . $payment->id . '"', false);
    }

    public function test_halaman_kuota_gateway_memuat_draft_per_payment(): void
    {
        $payment = Payment::create([
            'workspace_id'   => null,
            'payment_type'   => Payment::PAYMENT_TYPE_QUOTA,
            'company_id'     => $this->company->id,
            'freelancer_id'  => null,
            'invoice_number' => 'INV-QUOTA-TEST-1',
            'amount'         => 10000,
            'status'         => 'pending',
        ]);

        $response = $this->asCompany('company.quota.payment.show', ['payment' => $payment->id]);

        $this->assertCompanyDraftPage($response);

        $response->assertSee('data-draft-key="co:quota-payment:' . $payment->id . '"', false);
    }

    public function test_halaman_kuota_gateway_mempertahankan_draft_buat_proyek(): void
    {
        $payment = $this->makeQuotaPayment();

        $response = $this->asCompany('company.quota.payment.show', ['payment' => $payment->id]);

        $this->assertCompanyDraftPage($response);

        // REGRESSION: halaman gateway adalah halaman PERANTARA dari form "Buat
        // Proyek". User di sini belum tentu selesai membuat proyek — dia hanya
        // sedang membayar kuota. Tanpa penanda ini, engine menganggap perpindahan
        // halaman sebagai "submit sukses" dan draft co:project-create terhapus,
        // sehingga form Buat Proyek kosong saat user menekan "Kembali ke Buat Proyek".
        $response->assertSee('data-draft-keep-pending="co:project-create"', false);

        // Halaman ini tidak boleh mengirim penanda hapus draft untuk form create.
        $response->assertDontSee('data-draft-clear="co:project-create"', false);
        $response->assertDontSee('data-draft-clear-prefix="co:project-create', false);
    }

    /**
     * REGRESSION: link "Kembali ke Buat Proyek" pada halaman pembayaran harus
     * menunjuk route create yang stabil (tanpa query yang mengubah draft key)
     * dan tidak boleh memuat atribut perusak draft.
     */
    public function test_link_kembali_ke_buat_proyek_tidak_menghapus_draft(): void
    {
        $payment = $this->makeQuotaPayment();

        $response = $this->asCompany('company.quota.payment.show', ['payment' => $payment->id]);

        $response->assertSee('Kembali ke Buat Proyek', false);
        $response->assertSee(route('company.projects.create'), false);

        // Tidak ada satu pun penanda hapus draft untuk form create di halaman ini.
        $response->assertDontSee('data-draft-clear="co:project-create"', false);
        $response->assertDontSee('data-draft-discard', false);
    }

    /**
     * REGRESSION: saat store() diblokir kuota, controller mengarahkan user ke
     * halaman create (bukan ke gateway) dengan session quota_payment_id, dan
     * TIDAK boleh mengirim penanda hapus draft — proyek belum berhasil dibuat.
     */
    public function test_store_terblokir_kuota_tidak_menghapus_draft_buat_proyek(): void
    {
        \App\Models\FinancialSetting::getSettings();

        $response = $this->actingAs($this->company)
            ->from(route('company.projects.create'))
            ->post(route('company.projects.store'), [
                'project_name'        => 'Website E-commerce',
                'project_description' => 'Butuh landing page + CMS',
                'budget'              => 5000000,
                'deadline'            => now()->addMonth()->toDateString(),
                'skills'              => 'PHP,Laravel',
                'status'              => Project::STATUS_OPEN,
            ]);

        $response->assertRedirect(route('company.projects.create'));
        $response->assertSessionHas('quota_payment_id');
        $response->assertSessionMissing('draft_clear');
        $response->assertSessionMissing('draft_clear_prefix');
    }

    /** Payment kuota pending milik company pada setUp(). */
    private function makeQuotaPayment(): Payment
    {
        return Payment::create([
            'workspace_id'   => null,
            'payment_type'   => Payment::PAYMENT_TYPE_QUOTA,
            'company_id'     => $this->company->id,
            'freelancer_id'  => null,
            'invoice_number' => 'INV-QUOTA-KEMBALI-' . $this->company->id,
            'amount'         => 10000,
            'status'         => 'pending',
        ]);
    }

    public function test_workspace_company_memuat_draft_company_tanpa_membocorkan_milik_freelancer(): void
    {
        $response = $this->asCompany('company.workspaces.show', ['workspace' => $this->workspace->id]);

        $this->assertCompanyDraftPage($response);

        // Form Company pada halaman workspace ini.
        $response->assertSee('data-draft-key="co:ws-message:' . $this->workspace->id . '"', false);
        $response->assertSee('data-draft-key="co:ws-addstage:' . $this->workspace->id . '"', false);
        $response->assertSee('data-draft-key="co:ws-submission-accept:' . $this->workspace->id . '"', false);
        $response->assertSee('data-draft-key="co:ws-submission-revision:' . $this->workspace->id . '"', false);

        // Form & penanda khusus Freelancer tidak boleh muncul di halaman Company.
        $response->assertDontSee('data-draft-key="fl:ws-addstage:' . $this->workspace->id . '"', false);
        $response->assertDontSee('data-draft-key="fl:ws-modal:' . $this->workspace->id . '"', false);
        $response->assertDontSee('data-draft-key="fl:ws-note:' . $this->workspace->id . '"', false);
        $response->assertDontSee('data-draft-key="fl:ws-submission-upload:' . $this->workspace->id . '"', false);
    }

    /**
     * Form bukti tambahan Company hanya berisi input file (tidak ada field teks)
     * sehingga sengaja TIDAK dipasangi draft — file tidak pernah disimpan engine.
     */
    public function test_form_bukti_tambahan_company_sengaja_tidak_dipasangi_draft(): void
    {
        $report = Report::create([
            'reporter_id'      => $this->company->id,
            'reported_user_id' => $this->freelancer->id,
            'subject'          => 'Bukti hasil kerja tidak sesuai',
            'description'      => 'Perlu bukti tambahan.',
            'status'           => Report::STATUS_MENUNGGU_BUKTI,
            'category'         => Report::CATEGORY_HASIL_TIDAK_SESUAI,
            'target'           => Report::TARGET_FREELANCER,
        ]);

        $response = $this->asCompany('company.reports.show', ['report' => $report->id]);

        $response->assertOk();
        $response->assertSee('company/reports/' . $report->id . '/evidence', false);
        $response->assertDontSee('data-draft-form', false);
    }

    /** Payment workspace + status workspace "Menunggu Pembayaran". */
    private function makeWorkspacePayment(): Payment
    {
        $this->workspace->update(['status' => 'Menunggu Pembayaran']);

        return Payment::create([
            'workspace_id'   => $this->workspace->id,
            'payment_type'   => Payment::PAYMENT_TYPE_WORKSPACE,
            'company_id'     => $this->company->id,
            'freelancer_id'  => $this->freelancer->id,
            'invoice_number' => 'INV-TEST-1',
            'amount'         => 1000000,
            'status'         => 'pending',
        ]);
    }

    /** Submission berstatus pending milik workspace pada setUp(). */
    private function pendingSubmission(): ProjectSubmission
    {
        return ProjectSubmission::create([
            'workspace_id' => $this->workspace->id,
            'submitted_by' => $this->freelancer->id,
            'title'        => 'Hasil Pekerjaan Tahap 1',
            'status'       => 'pending',
        ]);
    }

    public function test_penanda_clear_draft_dari_flash_session_dirender_di_halaman_company(): void
    {
        $response = $this->actingAs($this->company)
            ->withSession([
                'draft_clear'        => 'co:ws-message:' . $this->workspace->id,
                'draft_clear_prefix' => 'co:ws-submission-revision:' . $this->workspace->id . ':',
            ])
            ->get(route('company.workspaces.show', $this->workspace));

        $response->assertOk();
        $response->assertSee('data-draft-clear="co:ws-message:' . $this->workspace->id . '"', false);
        $response->assertSee('data-draft-clear-prefix="co:ws-submission-revision:' . $this->workspace->id . ':"', false);
    }

    public function test_tanpa_flash_sukses_tidak_ada_penanda_clear_draft(): void
    {
        $this->actingAs($this->company)
            ->get(route('company.workspaces.show', $this->workspace))
            ->assertOk()
            ->assertDontSee('data-draft-clear="co:ws-message:' . $this->workspace->id . '"', false);
    }

    public function test_kirim_pesan_workspace_company_menandai_clear_draft_chat(): void
    {
        $response = $this->actingAs($this->company)
            ->post(route('company.workspaces.message', $this->workspace), ['message' => 'Halo, mohon dipercepat.']);

        $response->assertRedirect(route('company.workspaces.show', $this->workspace));
        $response->assertSessionHas('draft_clear', 'co:ws-message:' . $this->workspace->id);
    }

    public function test_aksi_tambah_dan_rename_tahap_company_menandai_clear_draft(): void
    {
        $add = $this->actingAs($this->company)->post(route('company.workspaces.progress', $this->workspace), [
            'action'    => 'add',
            'new_stage' => 'Riset Kebutuhan',
        ]);

        $add->assertSessionHas('draft_clear', 'co:ws-addstage:' . $this->workspace->id);

        $rename = $this->actingAs($this->company)->post(route('company.workspaces.progress', $this->workspace), [
            'action'    => 'rename',
            'old_stage' => 'Riset Kebutuhan',
            'new_stage' => 'Riset & Analisis',
        ]);

        $rename->assertSessionHas('draft_clear_prefix', 'co:ws-stage-rename:' . $this->workspace->id . ':');
    }

    public function test_terima_submission_menandai_clear_prefix_draft_modal(): void
    {
        $submission = $this->pendingSubmission();

        $response = $this->actingAs($this->company)->post(
            route('company.workspaces.submissions.accept', [
                'workspace'  => $this->workspace->id,
                'submission' => $submission->id,
            ]),
            ['company_note' => 'Hasil sudah sesuai.']
        );

        $response->assertRedirect(route('company.workspaces.show', $this->workspace));
        $response->assertSessionHas('draft_clear_prefix', 'co:ws-submission-accept:' . $this->workspace->id . ':');
    }

    public function test_minta_revisi_submission_menandai_clear_prefix_draft_modal(): void
    {
        $submission = $this->pendingSubmission();

        $response = $this->actingAs($this->company)->post(
            route('company.workspaces.submissions.revision', [
                'workspace'  => $this->workspace->id,
                'submission' => $submission->id,
            ]),
            ['company_note' => 'Mohon perbaiki halaman login.']
        );

        $response->assertRedirect(route('company.workspaces.show', $this->workspace));
        $response->assertSessionHas('draft_clear_prefix', 'co:ws-submission-revision:' . $this->workspace->id . ':');
    }
}
