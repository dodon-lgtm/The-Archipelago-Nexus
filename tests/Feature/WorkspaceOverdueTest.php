<?php

namespace Tests\Feature;

use App\Models\CompanyAccountRequest;
use App\Models\Notification;
use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceOverdueTest extends TestCase
{
    use RefreshDatabase;

    private User $company;
    private User $freelancer;

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
    }

    private function createWorkspace(string $status, int $daysFromToday): Workspace
    {
        $project = Project::factory()->create([
            'user_id'  => $this->company->id,
            'deadline' => Carbon::today()->addDays($daysFromToday)->toDateString(),
            'status'   => Project::STATUS_OPEN,
        ]);

        return Workspace::create([
            'project_id'    => $project->id,
            'company_id'    => $this->company->id,
            'freelancer_id' => $this->freelancer->id,
            'status'        => $status,
        ]);
    }

    private function runCommand(): void
    {
        $this->artisan('workspaces:mark-overdue')->assertSuccessful();
    }

    public function test_overdue_marks_sedang_dikerjakan_and_notifies_both_parties(): void
    {
        $workspace = $this->createWorkspace('Sedang Dikerjakan', -2);

        $this->runCommand();

        $fresh = $workspace->fresh();
        $this->assertSame('Melewati Batas Waktu', $fresh->status);
        $this->assertSame('Sedang Dikerjakan', $fresh->overdue_previous_status);

        $notifications = Notification::where('type', 'workspace.overdue')->get();
        $this->assertCount(2, $notifications);

        $companyNotif = $notifications->firstWhere('user_id', $this->company->id);
        $this->assertNotNull($companyNotif);
        $this->assertSame('Deadline Terlewat', $companyNotif->title);
        $this->assertStringContainsString('belum selesai sampai deadline', $companyNotif->message);
        $this->assertSame($workspace->id, $companyNotif->workspace_id);
        $this->assertSame(route('company.workspaces.show', $workspace), $companyNotif->data['redirect']);

        $freelancerNotif = $notifications->firstWhere('user_id', $this->freelancer->id);
        $this->assertNotNull($freelancerNotif);
        $this->assertStringContainsString('telah terlewat dan pekerjaan belum selesai', $freelancerNotif->message);
        $this->assertSame($workspace->id, $freelancerNotif->workspace_id);
        $this->assertSame(route('freelancer.workspaces.show', $workspace), $freelancerNotif->data['redirect']);

        $this->assertFalse($companyNotif->is_read);
        $this->assertFalse($freelancerNotif->is_read);
    }

    public function test_overdue_marks_menunggu_revisi(): void
    {
        $workspace = $this->createWorkspace('Menunggu Revisi', -5);

        $this->runCommand();

        $fresh = $workspace->fresh();
        $this->assertSame('Melewati Batas Waktu', $fresh->status);
        $this->assertSame('Menunggu Revisi', $fresh->overdue_previous_status);
        $this->assertSame(2, Notification::where('type', 'workspace.overdue')->count());
    }

    public function test_deadline_today_is_not_marked_overdue(): void
    {
        $workspace = $this->createWorkspace('Sedang Dikerjakan', 0);

        $this->runCommand();

        $this->assertSame('Sedang Dikerjakan', $workspace->fresh()->status);
        $this->assertSame(0, Notification::where('type', 'workspace.overdue')->count());
    }

    public function test_non_flagable_statuses_are_not_marked(): void
    {
        foreach (['Selesai', 'Menunggu Pembayaran', 'Menunggu Verifikasi Admin', 'Menunggu Review'] as $status) {
            $workspace = $this->createWorkspace($status, -3);
            $this->runCommand();
            $this->assertSame($status, $workspace->fresh()->status);
        }

        $this->assertSame(0, Notification::where('type', 'workspace.overdue')->count());
    }

    public function test_command_is_idempotent(): void
    {
        $this->createWorkspace('Sedang Dikerjakan', -2);

        $this->runCommand();
        $this->runCommand();

        $this->assertSame(2, Notification::where('type', 'workspace.overdue')->count());
    }

    public function test_melewati_batas_waktu_status_renders_on_company_and_freelancer_workspace_pages(): void
    {
        $workspace = $this->createWorkspace('Sedang Dikerjakan', -2);
        $this->runCommand();
        $this->assertSame('Melewati Batas Waktu', $workspace->fresh()->status);

        $companyResponse = $this->actingAs($this->company)
            ->get(route('company.workspaces.show', $workspace))
            ->assertOk();
        $companyResponse->assertSee('Melewati Batas Waktu', false);

        $freelancerResponse = $this->actingAs($this->freelancer)
            ->get(route('freelancer.workspaces.show', $workspace))
            ->assertOk();
        $freelancerResponse->assertSee('Melewati Batas Waktu', false);
    }

    public function test_melewati_batas_waktu_status_shows_in_company_and_freelancer_index(): void
    {
        $this->createWorkspace('Sedang Dikerjakan', -2);
        $this->runCommand();

        $companyIndex = $this->actingAs($this->company)
            ->get(route('company.workspaces.index'))
            ->assertOk();
        $companyIndex->assertSee('Melewati Batas Waktu', false);

        $freelancerIndex = $this->actingAs($this->freelancer)
            ->get(route('freelancer.workspaces.index'))
            ->assertOk();
        $freelancerIndex->assertSee('Melewati Batas Waktu', false);
    }

    public function test_deadline_moved_back_restores_sedang_dikerjakan(): void
    {
        $workspace = $this->createWorkspace('Sedang Dikerjakan', -2);

        $this->runCommand();
        $this->assertSame('Melewati Batas Waktu', $workspace->fresh()->status);

        // Simulasi deadline dimundurkan/diperpanjang: belum lewat lagi.
        $workspace->project->update(['deadline' => Carbon::today()->addDays(3)->toDateString()]);

        $this->runCommand();

        $fresh = $workspace->fresh();
        $this->assertSame('Sedang Dikerjakan', $fresh->status);
        $this->assertNull($fresh->overdue_previous_status);

        // Revert tidak menambah notifikasi (notif tetap 2 dari proses flag).
        $this->assertSame(2, Notification::where('type', 'workspace.overdue')->count());
    }

    public function test_deadline_moved_back_restores_menunggu_revisi(): void
    {
        $workspace = $this->createWorkspace('Menunggu Revisi', -4);

        $this->runCommand();
        $this->assertSame('Melewati Batas Waktu', $workspace->fresh()->status);

        $workspace->project->update(['deadline' => Carbon::today()->addDays(7)->toDateString()]);

        $this->runCommand();

        $fresh = $workspace->fresh();
        $this->assertSame('Menunggu Revisi', $fresh->status);
        $this->assertNull($fresh->overdue_previous_status);
        $this->assertSame(2, Notification::where('type', 'workspace.overdue')->count());
    }

    public function test_deadline_today_counts_as_not_overdue_for_revert(): void
    {
        $workspace = $this->createWorkspace('Sedang Dikerjakan', -2);

        $this->runCommand();
        $this->assertSame('Melewati Batas Waktu', $workspace->fresh()->status);

        // Deadline tepat hari ini = belum lewat (konsisten dengan aturan flag).
        $workspace->project->update(['deadline' => Carbon::today()->toDateString()]);

        $this->runCommand();

        $this->assertSame('Sedang Dikerjakan', $workspace->fresh()->status);
    }

    public function test_workspace_still_overdue_is_not_reverted(): void
    {
        $workspace = $this->createWorkspace('Sedang Dikerjakan', -2);

        $this->runCommand();
        $this->assertSame('Melewati Batas Waktu', $workspace->fresh()->status);

        // Deadline masih lewat: tetap Melewati Batas Waktu, tidak ada notifikasi ganda.
        $this->runCommand();

        $fresh = $workspace->fresh();
        $this->assertSame('Melewati Batas Waktu', $fresh->status);
        $this->assertSame('Sedang Dikerjakan', $fresh->overdue_previous_status);
        $this->assertSame(2, Notification::where('type', 'workspace.overdue')->count());
    }

    public function test_legacy_melewati_batas_waktu_without_origin_falls_back_to_sedang_dikerjakan(): void
    {
        // Workspace lama berstatus Melewati Batas Waktu tanpa catatan status asal.
        $workspace = $this->createWorkspace('Melewati Batas Waktu', 3);

        $this->runCommand();

        $fresh = $workspace->fresh();
        $this->assertSame('Sedang Dikerjakan', $fresh->status);
        $this->assertNull($fresh->overdue_previous_status);
        $this->assertSame(0, Notification::where('type', 'workspace.overdue')->count());
    }

    public function test_revert_does_not_touch_other_statuses(): void
    {
        foreach (['Selesai', 'Menunggu Pembayaran', 'Menunggu Verifikasi Admin', 'Menunggu Review'] as $status) {
            $workspace = $this->createWorkspace($status, 2);

            $this->runCommand();

            $this->assertSame($status, $workspace->fresh()->status);
        }

        $this->assertSame(0, Notification::where('type', 'workspace.overdue')->count());
    }
}