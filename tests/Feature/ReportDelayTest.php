<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use App\Models\Workspace;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportDelayTest extends TestCase
{
    use RefreshDatabase;

    private User $company;
    private User $freelancer;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = User::factory()->create(['role' => 'company']);
        CompanyAccountRequest::create([
            'company_name'    => $this->company->name,
            'contact_person'  => $this->company->name,
            'company_email'   => $this->company->email,
            'company_phone'   => '081234567890',
            'company_address' => 'Alamat Perusahaan',
            'request_status'  => 'disetujui',
        ]);
        $this->freelancer = User::factory()->create(['role' => 'freelancer']);
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    private function createOverdueWorkspace(): Workspace
    {
        $project = Project::factory()->create([
            'user_id'      => $this->company->id,
            'deadline'     => Carbon::today()->subDays(5)->toDateString(),
            'status'       => Project::STATUS_OPEN,
        ]);

        return Workspace::create([
            'project_id'             => $project->id,
            'company_id'             => $this->company->id,
            'freelancer_id'          => $this->freelancer->id,
            'status'                 => 'Melewati Batas Waktu',
            'overdue_previous_status'=> 'Sedang Dikerjakan',
        ]);
    }

    private function delayReportPayload(Workspace $workspace): array
    {
        return [
            'workspace_id'     => $workspace->id,
            'project_id'       => $workspace->project_id,
            'reported_user_id' => $this->freelancer->id,
            'category'         => Report::CATEGORY_KETERLAMBATAN,
            'subject'          => 'Keterlambatan penyelesaian proyek "' . $workspace->project->project_name . '"',
            'description'      => 'Freelancer belum menyelesaikan pekerjaan hingga batas akhir. Mohon ditinjau.',
        ];
    }

    public function test_company_can_report_delay_from_overdue_workspace(): void
    {
        $workspace = $this->createOverdueWorkspace();

        // Tombol "Laporkan Keterlambatan" tampil di halaman workspace company
        // saat status Melewati Batas Waktu.
        $this->actingAs($this->company)
            ->get(route('company.workspaces.show', $workspace))
            ->assertOk()
            ->assertSee('Laporkan Keterlambatan');

        // Company mengirim laporan keterlambatan.
        $response = $this->actingAs($this->company)
            ->post(route('company.reports.store'), $this->delayReportPayload($workspace));

        $response->assertRedirect(route('company.workspaces.show', $workspace));

        // Laporan dibuat dengan konteks & kategori yang benar.
        $report = Report::first();
        $this->assertNotNull($report);
        $this->assertSame($this->company->id, $report->reporter_id);
        $this->assertSame($this->freelancer->id, $report->reported_user_id);
        $this->assertSame($workspace->id, $report->workspace_id);
        $this->assertSame($workspace->project_id, $report->project_id);
        $this->assertSame(Report::TARGET_FREELANCER, $report->target);
        $this->assertSame(Report::CATEGORY_KETERLAMBATAN, $report->category);
        $this->assertSame(Report::STATUS_MENUNGGU, $report->status);

        // Admin mendapat notifikasi laporan baru (sistem existing).
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->admin->id,
            'type'    => 'report.created',
        ]);

        // Freelancer mendapat notifikasi bahwa keterlambatannya dilaporkan.
        $freelancerNotif = Notification::where('user_id', $this->freelancer->id)
            ->where('type', 'report.delay_submitted')
            ->first();
        $this->assertNotNull($freelancerNotif);
        $this->assertFalse($freelancerNotif->is_read);
        $this->assertSame($workspace->id, $freelancerNotif->workspace_id);
        $this->assertSame(route('freelancer.workspaces.show', $workspace), $freelancerNotif->data['redirect']);

        // Tidak ada perubahan otomatis: status workspace, project, dan dana.
        $this->assertSame('Melewati Batas Waktu', $workspace->fresh()->status);
        $this->assertSame('Sedang Dikerjakan', $workspace->fresh()->overdue_previous_status);
        $this->assertSame(Project::STATUS_OPEN, $workspace->project->fresh()->status);
        $this->assertNull($workspace->fresh()->payment);
    }

    public function test_company_outside_workspace_cannot_report_delay(): void
    {
        $workspace = $this->createOverdueWorkspace();
        $intruder = User::factory()->create(['role' => 'company']);
        CompanyAccountRequest::create([
            'company_name'    => $intruder->name,
            'contact_person'  => $intruder->name,
            'company_email'   => $intruder->email,
            'company_phone'   => '081234567890',
            'company_address' => 'Alamat Perusahaan Lain',
            'request_status'  => 'disetujui',
        ]);

        $this->actingAs($intruder)
            ->from(route('company.workspaces.show', $workspace))
            ->post(route('company.reports.store'), $this->delayReportPayload($workspace))
            ->assertRedirect(route('company.workspaces.show', $workspace))
            ->assertSessionHasErrors();

        $this->assertSame(0, Report::count());
    }

    public function test_delay_report_is_blocked_while_active_report_exists(): void
    {
        $workspace = $this->createOverdueWorkspace();

        $this->actingAs($this->company)
            ->from(route('company.workspaces.show', $workspace))
            ->post(route('company.reports.store'), $this->delayReportPayload($workspace));

        // Laporan kedua untuk workspace yang sama ditolak oleh anti-spam existing.
        $this->actingAs($this->company)
            ->from(route('company.workspaces.show', $workspace))
            ->post(route('company.reports.store'), $this->delayReportPayload($workspace))
            ->assertRedirect(route('company.workspaces.show', $workspace))
            ->assertSessionHasErrors('subject');

        $this->assertSame(1, Report::count());
    }

    public function test_freelancer_does_not_see_report_button(): void
    {
        $workspace = $this->createOverdueWorkspace();

        $this->actingAs($this->freelancer)
            ->get(route('freelancer.workspaces.show', $workspace))
            ->assertOk()
            ->assertDontSee('Laporkan Keterlambatan');
    }
}