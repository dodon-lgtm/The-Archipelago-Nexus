<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression test auto-save draft form Freelancer
 * (public/js/form-draft-autosave.js + partials/form-draft-autosave.blade.php).
 *
 * Yang dijaga di sini adalah KONTRAK SERVER-SIDE:
 *  - halaman freelancer memuat engine draft dan menandai form-nya
 *    dengan data-draft-form + data-draft-key yang benar;
 *  - hidden penting (harga_penawaran) memakai data-draft-include;
 *  - penanda sukses (data-draft-clear) hanya muncul saat ada flash sukses;
 *  - halaman Company TIDAK memuat engine/atribut draft (guard role).
 *
 * Perilaku engine sendiri (debounce, pending, prune storage, dst) diuji di
 * tests/js/form-draft-autosave.test.js.
 */
class FreelancerFormDraftAutosaveTest extends TestCase
{
    use RefreshDatabase;

    private User $freelancer;
    private User $company;
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

    private function asFreelancer(string $routeName, array $params = []): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($this->freelancer)->get(route($routeName, $params));
    }

    /**
     * Project tanpa workspace — wajib untuk halaman Kirim Penawaran, karena
     * Project::acceptsOffers() menolak project yang sudah punya workspace.
     */
    private function openProjectWithoutWorkspace(): Project
    {
        return Project::factory()->create([
            'user_id' => $this->company->id,
            'status'  => Project::STATUS_OPEN,
        ]);
    }

    public function test_edit_profil_freelancer_memuat_engine_draft(): void
    {
        $response = $this->asFreelancer('freelancer.profile.edit');

        $response->assertOk();
        $response->assertSee('js/form-draft-autosave.js', false);
        $response->assertSee('data-draft-form', false);
        $response->assertSee('data-draft-key="fl:profile:' . $this->freelancer->id . '"', false);
    }

    public function test_halaman_penawaran_memuat_draft_termasuk_hidden_harga(): void
    {
        $project = $this->openProjectWithoutWorkspace();

        $response = $this->asFreelancer('freelancer.penawaran.create', ['project' => $project->id]);

        $response->assertOk();
        $response->assertSee('js/form-draft-autosave.js', false);
        $response->assertSee('data-draft-key="fl:penawaran:' . $project->id . '"', false);
        $response->assertSee('data-draft-include', false); // hidden harga_penawaran
    }

    public function test_halaman_pendapatan_dan_form_withdraw_terhubung_ke_draft(): void
    {
        $response = $this->asFreelancer('freelancer.pendapatan.index');

        $response->assertOk();
        $response->assertSee('js/form-draft-autosave.js', false);
        $response->assertSee('data-draft-key="fl:withdraw:' . $this->freelancer->id . '"', false);
    }

    public function test_penanda_sukses_withdraw_menghapus_draft(): void
    {
        // Tanpa flash sukses → tidak ada penanda yang memerintahkan hapus draft.
        $this->asFreelancer('freelancer.pendapatan.index')
            ->assertOk()
            ->assertDontSee('data-draft-clear="fl:withdraw:' . $this->freelancer->id . '"', false);

        $response = $this->actingAs($this->freelancer)
            ->withSession(['success' => 'Penarikan dana berhasil diajukan.'])
            ->get(route('freelancer.pendapatan.index'));

        $response->assertOk();
        $response->assertSee('data-draft-clear="fl:withdraw:' . $this->freelancer->id . '"', false);
    }

    public function test_halaman_buat_laporan_memuat_draft(): void
    {
        $response = $this->asFreelancer('freelancer.reports.create', ['reported_user_id' => $this->company->id]);

        $response->assertOk();
        $response->assertSee('js/form-draft-autosave.js', false);
        $response->assertSee(
            'data-draft-key="fl:report-create:0-0-' . $this->company->id . '"',
            false
        );
    }

    public function test_workspace_freelancer_memuat_draft_dan_penanda_sukses_submission(): void
    {
        $response = $this->asFreelancer('freelancer.workspaces.show', ['workspace' => $this->workspace->id]);

        $response->assertOk();
        $response->assertSee('js/form-draft-autosave.js', false);
        $response->assertSee('data-draft-key="fl:ws-addstage:' . $this->workspace->id . '"', false);
        $response->assertSee('data-draft-key="fl:ws-modal:' . $this->workspace->id . '"', false);
        $response->assertSee('data-draft-key="fl:ws-submission-upload:' . $this->workspace->id . '"', false);

        $withSuccess = $this->actingAs($this->freelancer)
            ->withSession(['success' => 'Hasil pekerjaan berhasil dikirim.'])
            ->get(route('freelancer.workspaces.show', $this->workspace));

        $withSuccess->assertOk();
        $withSuccess->assertSee('data-draft-clear="fl:ws-submission:' . $this->workspace->id . '"', false);
    }

    public function test_halaman_workspace_company_tidak_memuat_engine_draft(): void
    {
        $response = $this->actingAs($this->company)
            ->get(route('company.workspaces.show', $this->workspace));

        $response->assertOk();

        // Guard role: engine tidak boleh dimuat untuk Company.
        $response->assertDontSee('js/form-draft-autosave.js', false);
        $response->assertDontSee('apexforge.fl.draft.v1', false);

        // Atribut draft yang khusus Freelancer juga tidak boleh muncul.
        $response->assertDontSee('data-draft-key="fl:ws-addstage:' . $this->workspace->id . '"', false);
        $response->assertDontSee('data-draft-key="fl:ws-modal:' . $this->workspace->id . '"', false);
    }

    public function test_admin_tidak_dapat_membuka_halaman_freelancer_dan_tidak_memuat_engine(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Middleware ensureFreelancer menolak admin dari halaman freelancer.
        $this->actingAs($admin)
            ->get(route('freelancer.profile.edit'))
            ->assertStatus(403);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('js/form-draft-autosave.js', false);
    }

    public function test_form_catatan_progress_memuat_draft_per_tahap(): void
    {
        // Company menambah tahap agar form catatan progress Freelancer dirender.
        $this->actingAs($this->company)
            ->post("/company/workspaces/{$this->workspace->id}/progress", [
                'action'    => 'add',
                'new_stage' => 'Integrasi Pembayaran',
            ])
            ->assertStatus(302);

        $response = $this->asFreelancer('freelancer.workspaces.show', ['workspace' => $this->workspace->id]);

        $response->assertOk();
        $response->assertSee('data-draft-key="fl:ws-note:' . $this->workspace->id . '"', false);
        // Hidden `stage` jadi pembeda key → draft catatan tidak bercampur antar tahap.
        $response->assertSee('data-draft-variant', false);
    }

    public function test_detail_laporan_memuat_draft_bukti_tambahan(): void
    {
        $report = Report::create([
            'reporter_id'      => $this->freelancer->id,
            'reported_user_id' => $this->company->id,
            'subject'          => 'Bukti hasil kerja tidak sesuai',
            'description'      => 'Perlu bukti tambahan.',
            'status'           => Report::STATUS_MENUNGGU_BUKTI,
            'category'         => Report::CATEGORY_HASIL_TIDAK_SESUAI,
            'target'           => Report::TARGET_COMPANY,
        ]);

        $response = $this->asFreelancer('freelancer.reports.show', ['report' => $report->id]);

        $response->assertOk();
        $response->assertSee('js/form-draft-autosave.js', false);
        $response->assertSee('data-draft-key="fl:report-evidence:' . $report->id . '"', false);

        $withSuccess = $this->actingAs($this->freelancer)
            ->withSession(['success' => 'Bukti tambahan berhasil dikirim.'])
            ->get(route('freelancer.reports.show', $report));

        $withSuccess->assertOk();
        $withSuccess->assertSee('data-draft-clear="fl:report-evidence:' . $report->id . '"', false);
    }
}
