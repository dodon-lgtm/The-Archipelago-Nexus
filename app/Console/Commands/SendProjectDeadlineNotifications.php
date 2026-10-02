<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Project;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Mengirim notifikasi pengingat deadline proyek H-3 dan H-1
 * kepada Company (pemilik proyek) dan Freelancer (workspace aktif).
 *
 * Aturan:
 * - Hanya proyek yang sudah memiliki Workspace (freelancer terpilih)
 *   dan Workspace-nya belum berstatus "Selesai" yang diberi pengingat.
 * - Idempoten: tidak membuat notifikasi ganda per (user, proyek, level).
 */
class SendProjectDeadlineNotifications extends Command
{
    protected $signature = 'notifications:project-deadline';

    protected $description = 'Kirim notifikasi deadline proyek H-3 dan H-1 ke company dan freelancer.';

    /**
     * Level pengingat: [selisih hari menuju deadline => marker metadata].
     */
    private const REMINDER_LEVELS = [
        3 => 'h3',
        1 => 'h1',
    ];

    public function handle(): int
    {
        $today = Carbon::today();

        foreach (self::REMINDER_LEVELS as $daysLeft => $level) {
            $targetDate = $today->copy()->addDays($daysLeft);

            $projects = Project::query()
                ->whereNotNull('deadline')
                ->whereDate('deadline', '=', $targetDate->toDateString())
                ->with('workspace')
                ->get();

            foreach ($projects as $project) {
                $this->handleProject($project, $daysLeft, $level);
            }
        }

        $this->info('Notifikasi deadline proyek selesai diproses.');

        return self::SUCCESS;
    }

    private function handleProject(Project $project, int $daysLeft, string $level): void
    {
        $workspace = $project->workspace;

        // Hanya proyek yang sedang/akan dikerjakan (freelancer terpilih, belum selesai).
        if (!$workspace || $workspace->status === 'Selesai') {
            return;
        }

        $message = 'Deadline project ' . $project->project_name . ' tinggal ' . $daysLeft . ' hari.';

        $recipients = [
            [
                'user_id'  => (int) $project->user_id,
                'redirect' => route('company.workspaces.show', $workspace),
            ],
            [
                'user_id'  => (int) $workspace->freelancer_id,
                'redirect' => route('freelancer.workspaces.show', $workspace),
            ],
        ];

        foreach ($recipients as $recipient) {
            if ($recipient['user_id'] <= 0) {
                continue;
            }

            $this->sendReminder(
                $recipient['user_id'],
                $project,
                $workspace->id,
                $recipient['redirect'],
                $message,
                $level
            );
        }
    }

    private function sendReminder(
        int $userId,
        Project $project,
        int $workspaceId,
        string $redirect,
        string $message,
        string $level
    ): void {
        $alreadySent = Notification::query()
            ->where('user_id', $userId)
            ->where('project_id', $project->id)
            ->where('type', 'project.deadline')
            ->get()
            ->contains(function (Notification $notification) use ($level) {
                $data = $notification->data;

                return is_array($data) && ($data['deadline_level'] ?? null) === $level;
            });

        if ($alreadySent) {
            return;
        }

        NotificationService::sendTo(
            user: $userId,
            type: 'project.deadline',
            title: 'Deadline Mendekat',
            message: $message,
            redirect: $redirect,
            workspaceId: $workspaceId,
            projectId: $project->id,
            metadata: ['deadline_level' => $level],
        );
    }
}