<?php

namespace App\Console\Commands;

use App\Models\Workspace;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Mengelola status workspace berdasarkan deadline proyek (dua arah):
 *
 * 1. FLAG — deadline sudah lewat & pekerjaan belum selesai:
 *    Workspace 'Sedang Dikerjakan' / 'Menunggu Revisi' dengan deadline
 *    (tanggal) sudah lewat ditandai 'Melewati Batas Waktu', status asalnya
 *    disimpan di kolom overdue_previous_status, lalu notifikasi dikirim
 *    ke Freelancer dan Company.
 *
 * 2. REVERT — deadline belum lewat lagi (mis. dimundurkan/diperpanjang):
 *    Workspace berstatus 'Melewati Batas Waktu' dengan deadline yang masih
 *    belum lewat dikembalikan ke status asalnya masing-masing
 *    ('Sedang Dikerjakan' / 'Menunggu Revisi'). Revert tidak mengirim
 *    notifikasi. Bila status asal tidak tercatat (data lama), fallback
 *    ke 'Sedang Dikerjakan'.
 *
 * Aturan (mengikuti keputusan user):
 * - Hanya workspace berstatus 'Melewati Batas Waktu' hasil proses overdue
 *   yang di-revert. Status 'Selesai', 'Menunggu Pembayaran',
 *   'Menunggu Verifikasi Admin', 'Menunggu Review', dan status lain
 *   TIDAK pernah disentuh command ini.
 * - Batas "lewat" konsisten dua arah: deadline hari ini dianggap BELUM
 *   lewat (flag: deadline < hari ini, revert: deadline >= hari ini).
 * - Idempoten: flag tidak menghasilkan notifikasi ganda; revert hanya
 *   menyentuh workspace berstatus 'Melewati Batas Waktu'.
 * - Tidak menghapus workspace / pekerjaan. Tidak ada sanksi.
 */
class MarkOverdueWorkspaces extends Command
{
    protected $signature = 'workspaces:mark-overdue';

    protected $description = 'Tandai workspace sebagai Melewati Batas Waktu saat deadline lewat & pekerjaan belum selesai, dan kembalikan status asal saat deadline belum lewat lagi.';

    /**
     * Status yang berarti "pekerjaan belum selesai" → layak ditandai
     * Melewati Batas Waktu. Sekaligus kumpulan status asal yang valid
     * untuk dipulihkan saat revert.
     */
    private const FLAGGABLE_STATUSES = ['Sedang Dikerjakan', 'Menunggu Revisi'];

    private const OVERDUE_STATUS = 'Melewati Batas Waktu';

    public function handle(): int
    {
        $today = Carbon::today()->toDateString();

        $this->revertNotOverdueAnymore($today);
        $this->flagOverdue($today);

        $this->info('Workspace yang melewati batas waktu & pemulihannya selesai diproses.');

        return self::SUCCESS;
    }

    /**
     * Aturan REVERT: deadline belum lewat + status 'Melewati Batas Waktu'
     * → kembalikan ke status asal (overdue_previous_status), lalu bersihkan
     * catatan status asalnya. Tanpa notifikasi.
     */
    private function revertNotOverdueAnymore(string $today): void
    {
        Workspace::query()
            ->where('status', self::OVERDUE_STATUS)
            ->whereHas('project', function ($q) use ($today) {
                $q->whereNotNull('deadline')
                  ->whereDate('deadline', '>=', $today);
            })
            ->get()
            ->each(function (Workspace $workspace) {
                $this->revertToPreviousStatus($workspace);
            });
    }

    private function revertToPreviousStatus(Workspace $workspace): void
    {
        $previousStatus = $workspace->overdue_previous_status;

        // Data lama tanpa catatan status asal → fallback 'Sedang Dikerjakan'.
        if (!in_array($previousStatus, self::FLAGGABLE_STATUSES, true)) {
            $previousStatus = 'Sedang Dikerjakan';
        }

        $workspace->update([
            'status' => $previousStatus,
            'overdue_previous_status' => null,
        ]);
    }

    /**
     * Aturan FLAG: deadline sudah lewat + pekerjaan belum selesai
     * → tandai 'Melewati Batas Waktu' + simpan status asal + notifikasi.
     */
    private function flagOverdue(string $today): void
    {
        Workspace::query()
            ->with('project')
            ->whereIn('status', self::FLAGGABLE_STATUSES)
            ->whereHas('project', function ($q) use ($today) {
                $q->whereNotNull('deadline')
                  ->whereDate('deadline', '<', $today);
            })
            ->get()
            ->each(function (Workspace $workspace) {
                $this->markOverdue($workspace);
            });
    }

    private function markOverdue(Workspace $workspace): void
    {
        $project = $workspace->project;

        if (!$project) {
            return;
        }

        $previousStatus = $workspace->status;

        $workspace->update([
            'status' => self::OVERDUE_STATUS,
            'overdue_previous_status' => $previousStatus,
        ]);

        $deadlineLabel = Carbon::parse($project->deadline)->format('d M Y');

        // Notifikasi ke freelancer: deadline lewat & belum selesai.
        if ((int) $workspace->freelancer_id > 0) {
            NotificationService::sendTo(
                user: (int) $workspace->freelancer_id,
                type: 'workspace.overdue',
                title: 'Deadline Terlewat',
                message: 'Deadline proyek "' . $project->project_name . '" (' . $deadlineLabel . ') telah terlewat dan pekerjaan belum selesai. Segera selesaikan pekerjaan Anda.',
                redirect: route('freelancer.workspaces.show', $workspace),
                workspaceId: $workspace->id,
                projectId: $project->id,
            );
        }

        // Notifikasi ke company: pekerjaan belum selesai sampai deadline.
        if ((int) $workspace->company_id > 0) {
            NotificationService::sendTo(
                user: (int) $workspace->company_id,
                type: 'workspace.overdue',
                title: 'Deadline Terlewat',
                message: 'Pekerjaan untuk proyek "' . $project->project_name . '" belum selesai sampai deadline ' . $deadlineLabel . '.',
                redirect: route('company.workspaces.show', $workspace),
                workspaceId: $workspace->id,
                projectId: $project->id,
            );
        }
    }
}