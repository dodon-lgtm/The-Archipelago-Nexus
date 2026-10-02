<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Project;
use App\Models\User;
use App\Models\Workspace;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectDeadlineNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $company;
    private User $freelancer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = User::factory()->create(['role' => 'company']);
        $this->freelancer = User::factory()->create(['role' => 'freelancer']);
    }

    private function createProjectWithWorkspace(int $daysLeft, string $workspaceStatus = 'Sedang Dikerjakan'): array
    {
        $project = Project::factory()->create([
            'user_id'  => $this->company->id,
            'deadline' => Carbon::today()->addDays($daysLeft)->toDateString(),
            'status'   => Project::STATUS_OPEN,
        ]);

        $workspace = Workspace::create([
            'project_id'    => $project->id,
            'company_id'    => $this->company->id,
            'freelancer_id' => $this->freelancer->id,
            'status'        => $workspaceStatus,
        ]);

        return [$project, $workspace];
    }

    private function runCommand(): void
    {
        $this->artisan('notifications:project-deadline')->assertSuccessful();
    }

    public function test_h3_reminder_notifies_company_and_freelancer(): void
    {
        [$project, $workspace] = $this->createProjectWithWorkspace(3);

        $this->runCommand();

        $notifications = Notification::where('type', 'project.deadline')->get();
        $this->assertCount(2, $notifications);

        foreach ($notifications as $notification) {
            $this->assertSame($project->id, $notification->project_id);
            $this->assertSame($workspace->id, $notification->workspace_id);
            $this->assertSame('Deadline Mendekat', $notification->title);
            $this->assertSame(
                'Deadline project ' . $project->project_name . ' tinggal 3 hari.',
                $notification->message
            );
            $this->assertSame('h3', $notification->data['deadline_level']);
            $this->assertFalse($notification->is_read);
        }

        $companyNotif = $notifications->firstWhere('user_id', $this->company->id);
        $this->assertNotNull($companyNotif);
        $this->assertSame(route('company.workspaces.show', $workspace), $companyNotif->data['redirect']);

        $freelancerNotif = $notifications->firstWhere('user_id', $this->freelancer->id);
        $this->assertNotNull($freelancerNotif);
        $this->assertSame(route('freelancer.workspaces.show', $workspace), $freelancerNotif->data['redirect']);
    }

    public function test_h1_reminder_notifies_company_and_freelancer(): void
    {
        [$project] = $this->createProjectWithWorkspace(1);

        $this->runCommand();

        $notifications = Notification::where('type', 'project.deadline')->get();
        $this->assertCount(2, $notifications);

        foreach ($notifications as $notification) {
            $this->assertSame('h1', $notification->data['deadline_level']);
            $this->assertSame(
                'Deadline project ' . $project->project_name . ' tinggal 1 hari.',
                $notification->message
            );
        }
    }

    public function test_command_is_idempotent(): void
    {
        $this->createProjectWithWorkspace(3);

        $this->runCommand();
        $this->runCommand();

        $this->assertSame(2, Notification::where('type', 'project.deadline')->count());
    }

    public function test_no_notification_when_workspace_is_selesai(): void
    {
        $this->createProjectWithWorkspace(1, 'Selesai');

        $this->runCommand();

        $this->assertSame(0, Notification::where('type', 'project.deadline')->count());
    }

    public function test_no_notification_without_workspace(): void
    {
        Project::factory()->create([
            'user_id'  => $this->company->id,
            'deadline' => Carbon::today()->addDays(3)->toDateString(),
            'status'   => Project::STATUS_OPEN,
        ]);

        $this->runCommand();

        $this->assertSame(0, Notification::where('type', 'project.deadline')->count());
    }

    public function test_no_notification_for_far_deadline(): void
    {
        $this->createProjectWithWorkspace(7);

        $this->runCommand();

        $this->assertSame(0, Notification::where('type', 'project.deadline')->count());
    }

    public function test_h1_and_h3_reminders_are_independent(): void
    {
        $this->createProjectWithWorkspace(1);
        $this->createProjectWithWorkspace(3);

        $this->runCommand();

        $notifications = Notification::where('type', 'project.deadline')->get();
        $this->assertCount(4, $notifications);
        $this->assertSame(2, $notifications->filter(fn ($n) => ($n->data['deadline_level'] ?? null) === 'h1')->count());
        $this->assertSame(2, $notifications->filter(fn ($n) => ($n->data['deadline_level'] ?? null) === 'h3')->count());
    }
}