<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportAttachment;
use App\Models\User;
use App\Models\Project;
use App\Models\Penawaran;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * ReportService - Memusatkan logika pembuatan & pemrosesan laporan (V3).
 *
 * Report V3 memindahkan "source of truth" TARGET ke backend. Browser/form
 * hanya mengirim konteks (workspace_id, project_id, penawaran_id,
 * reported_user_id). Service ini yang menentukan `target` secara deterministik
 * dan memvalidasinya terhadap database, sehingga manipulasi URL/Postman/form
 * (anti-self-report, ganti-ganti target) ditolak.
 */
class ReportService
{
    /**
     * Buat laporan baru (Context Report / General Report).
     *
     * @param  array  $data Data yang sudah lolos ReportStoreRequest
     *                       (subject, description, category, konteks, dll)
     * @return Report
     */
    public function store(array $data): Report
    {
        // 1. Tentukan target OTOMATIS dari konteks (backend source of truth).
        $target = $this->resolveTarget($data);

        // 2. Validasi otorisasi relasi + anti-self-report + kategori-per-target.
        $this->authorizeStore($data, $target);

        return DB::transaction(function () use ($data, $target) {
            // 3. Anti-spam / cegah duplikat (reporter + target).
            $this->assertNoDuplicate($data, $target);

            // 4. Simpan laporan.
            $report = Report::create([
                'reporter_id'      => Auth::id(),
                'reported_user_id' => $data['reported_user_id'] ?? null,
                'project_id'       => $data['project_id'] ?? null,
                'penawaran_id'     => $data['penawaran_id'] ?? null,
                'workspace_id'     => $data['workspace_id'] ?? null,
                'target'           => $target,
                'subject'          => $data['subject'],
                'description'      => $data['description'],
                'category'         => $data['category'],
                'status'           => Report::STATUS_MENUNGGU,
            ]);

            // 5. Simpan lampiran/bukti (opsional).
            $this->storeAttachments($report, $data['attachments'] ?? null);

            // 6. Notifikasi admin hanya untuk report yang benar-benar baru.
            $this->notifyAdmins($report);

            return $report;
        });
    }

    /**
     * Unggah bukti/lampiran tambahan untuk laporan berstatus 'menunggu-bukti'.
     * Setelah bukti diunggah, status kembali ke 'ditinjau' agar admin meninjau ulang.
     */
    public function uploadEvidence(Report $report, array $files): Report
    {
        // Hanya pemilik laporan yang bisa mengunggah bukti.
        if ((int) $report->reporter_id !== (int) Auth::id()) {
            abort(403);
        }

        // Hanya boleh menambah bukti saat admin memintanya.
        if ($report->status !== Report::STATUS_MENUNGGU_BUKTI) {
            throw ValidationException::withMessages([
                'attachments' => 'Laporan ini tidak sedang meminta bukti tambahan.',
            ]);
        }

        DB::transaction(function () use ($report, $files) {
            $this->storeAttachments($report, $files);

            $report->update([
                'status'     => Report::STATUS_DITINJAU,
                'admin_note' => null,
            ]);

            $this->notifyAdmins($report, 'Bukti Tambahan: ' . $report->subject);
        });

        return $report->fresh();
    }

    /**
     * Tentukan target laporan secara deterministik dari konteks (backend).
     */
    protected function resolveTarget(array $data): string
    {
        $workspaceId = $data['workspace_id'] ?? null;
        $penawaranId = $data['penawaran_id'] ?? null;
        $projectId   = $data['project_id'] ?? null;
        $reportedId  = $data['reported_user_id'] ?? null;

        if ($workspaceId) {
            $workspace = Workspace::findOrFail($workspaceId);
            // Reporter = company -> target freelancer; reporter = freelancer -> target company.
            return (int) $workspace->company_id === (int) Auth::id()
                ? Report::TARGET_FREELANCER
                : Report::TARGET_COMPANY;
        }

        if ($penawaranId) {
            // Hanya company yang melaporkan penawaran -> target freelancer.
            return Report::TARGET_FREELANCER;
        }

        if ($projectId) {
            return Report::TARGET_PROJECT;
        }

// Pelaporan user murni (tanpa workspace/penawaran/project).
        // Target ditentukan dari ROLE user yang dilaporkan (backend source of truth).
        // Hanya relasi lintas-role yang valid:
        //   - Company -> freelancer  = TARGET_FREELANCER
        //   - Freelancer -> company  = TARGET_COMPANY
        // Kombinasi lain (freelancer->freelancer, company->company) ditolak.
        if ($reportedId) {
            $reportedUser = User::find($reportedId);
            if (!$reportedUser) {
                throw ValidationException::withMessages([
                    'reported_user_id' => 'Pengguna yang dilaporkan tidak ditemukan.',
                ]);
            }

            $reporterRole = Auth::user()->role;

            if ($reporterRole === 'freelancer' && $reportedUser->role === 'company') {
                return Report::TARGET_COMPANY;
            }

            if ($reporterRole === 'company' && $reportedUser->role === 'freelancer') {
                return Report::TARGET_FREELANCER;
            }

            // Kombinasi role lain tidak valid untuk pelaporan user murni.
            throw ValidationException::withMessages([
                'reported_user_id' => 'Anda tidak dapat melaporkan pengguna ini melalui alur laporan ini.',
            ]);
        }

        return Report::TARGET_WEBSITE;
    }

    /**
     * Validasi otorisasi relasi + anti-self-report + kategori-per-target.
     *
     * Semua ID dari request diverifikasi ulang terhadap database.
     *
     * @throws ValidationException
     */
    public function authorizeStore(array $data, string $target): void
    {
        $userId      = Auth::id();
        $reportedId  = $data['reported_user_id'] ?? null;
        $projectId   = $data['project_id'] ?? null;
        $penawaranId = $data['penawaran_id'] ?? null;
        $workspaceId = $data['workspace_id'] ?? null;
        $category    = $data['category'] ?? null;

        // 1. Kategori WAJIB valid untuk target.
        $allowedCategories = Report::categoriesForTarget($target);
        if (!in_array($category, $allowedCategories, true)) {
            throw ValidationException::withMessages([
                'category' => 'Kategori laporan tidak sesuai untuk target ' . Report::targetLabel($target) . '.',
            ]);
        }

        // 2. Anti self-report.
        if ($reportedId && (int) $reportedId === (int) $userId) {
            throw ValidationException::withMessages([
                'reported_user_id' => 'Anda tidak dapat melaporkan diri sendiri.',
            ]);
        }

        // 3. Website (general report) tidak pernah menargetkan user.
        if ($target === Report::TARGET_WEBSITE && $reportedId) {
            throw ValidationException::withMessages([
                'reported_user_id' => 'Laporan website/sistem tidak boleh menargetkan pengguna tertentu.',
            ]);
        }

        // 3b. Pelaporan user murni (company -> freelancer, freelancer -> company).
        // Tanpa workspace/penawaran/project. Backend menghitung target dari ROLE
        // user yang dilaporkan dan memvalidasi kembali terhadap database agar
        // manipulasi URL / reported_user_id tidak bisa menembus aturan.
        if ($reportedId && !$workspaceId && !$penawaranId && !$projectId) {
            $reportedUser = User::find($reportedId);
            if (!$reportedUser) {
                throw ValidationException::withMessages([
                    'reported_user_id' => 'Pengguna yang dilaporkan tidak ditemukan.',
                ]);
            }

            // Anti self-report sudah dicek di langkah 2.

            // Target harus cocok dengan role user yang dilaporkan.
            $expectedTarget = $reportedUser->role === 'company'
                ? Report::TARGET_COMPANY
                : Report::TARGET_FREELANCER;
            if ($target !== $expectedTarget) {
                throw ValidationException::withMessages([
                    'reported_user_id' => 'Target laporan tidak sesuai dengan pengguna yang dilaporkan.',
                ]);
            }

            // Company hanya boleh melaporkan freelancer.
            if (Auth::user()->role === 'company' && $reportedUser->role !== 'freelancer') {
                throw ValidationException::withMessages([
                    'reported_user_id' => 'Anda hanya dapat melaporkan freelancer.',
                ]);
            }

            // Freelancer hanya boleh melaporkan company.
            if (Auth::user()->role === 'freelancer' && $reportedUser->role !== 'company') {
                throw ValidationException::withMessages([
                    'reported_user_id' => 'Anda hanya dapat melaporkan perusahaan.',
                ]);
            }

            return;
        }

        // 4. Validasi konteks workspace (didahulukan).
        if ($workspaceId) {
            $workspace = Workspace::with('project')->find($workspaceId);
            if (!$workspace) {
                throw ValidationException::withMessages(['workspace_id' => 'Workspace tidak ditemukan.']);
            }

            $isCompany    = (int) $workspace->company_id === (int) $userId;
            $isFreelancer = (int) $workspace->freelancer_id === (int) $userId;

            if (!$isCompany && !$isFreelancer) {
                throw ValidationException::withMessages(['workspace_id' => 'Anda bukan bagian dari workspace ini.']);
            }

            // Target wajib pihak lawan.
            if ($reportedId) {
                $targetUserId = $isCompany ? $workspace->freelancer_id : $workspace->company_id;
                if ((int) $targetUserId !== (int) $reportedId) {
                    throw ValidationException::withMessages([
                        'reported_user_id' => 'Anda hanya dapat melaporkan pihak lawan pada workspace ini.',
                    ]);
                }
            }

            // Konsistensi project_id dengan workspace.
            if ($projectId && (int) $projectId !== (int) $workspace->project_id) {
                throw ValidationException::withMessages(['project_id' => 'Proyek tidak sesuai dengan workspace.']);
            }

            return;
        }

        // 5. Validasi konteks penawaran (company -> freelancer).
        if ($penawaranId) {
            $penawaran = Penawaran::with(['project', 'freelancer'])->find($penawaranId);
            if (!$penawaran) {
                throw ValidationException::withMessages(['penawaran_id' => 'Penawaran tidak ditemukan.']);
            }

            if ((int) $penawaran->project->user_id !== (int) $userId) {
                throw ValidationException::withMessages(['penawaran_id' => 'Anda tidak memiliki akses ke penawaran ini.']);
            }

            if ((int) $penawaran->freelancer_id !== (int) $reportedId) {
                throw ValidationException::withMessages([
                    'reported_user_id' => 'Pengguna yang dilaporkan tidak sesuai dengan penawaran.',
                ]);
            }

            if ($projectId && (int) $projectId !== (int) $penawaran->project_id) {
                throw ValidationException::withMessages(['project_id' => 'Proyek tidak sesuai dengan penawaran.']);
            }

            return;
        }

        // 6. Validasi konteks project (target project).
        if ($projectId) {
            $project = Project::with('owner')->find($projectId);
            if (!$project) {
                throw ValidationException::withMessages(['project_id' => 'Proyek tidak ditemukan.']);
            }

            if ($project->user_id == $userId) {
                throw ValidationException::withMessages(['project_id' => 'Anda tidak dapat melaporkan proyek Anda sendiri.']);
            }

            if ($reportedId && (int) $reportedId !== (int) $project->user_id) {
                throw ValidationException::withMessages([
                    'reported_user_id' => 'Pengguna yang dilaporkan tidak sesuai dengan pemilik proyek.',
                ]);
            }
        }
    }

/**
     * Anti-spam: cegah laporan duplikat untuk target yang sama.
     *
     * Kunci duplikat (reporter + target):
     *   - workspace  : reporter + workspace_id
     *   - project    : reporter + project_id
     *   - freelancer : reporter + reported_user_id
     *   - company    : reporter + reported_user_id
     *   - website    : reporter + subject (case-insensitive)
     *
     * Status aktif (menunggu/ditinjau/menunggu-bukti) memblokir laporan baru.
     * Status selesai/ditolak mengizinkan laporan baru untuk kasus yang sama.
     *
     * @throws ValidationException
     */
    protected function assertNoDuplicate(array $data, string $target): void
    {
        $reporterId = (int) Auth::id();
        $reportedId = $data['reported_user_id'] ?? null;
        $projectId  = $data['project_id'] ?? null;
        $workspaceId = $data['workspace_id'] ?? null;

        $query = Report::query()
            ->where('reporter_id', $reporterId)
            ->whereIn('status', Report::ACTIVE_STATUSES);

switch ($target) {
            case Report::TARGET_PROJECT:
                $query->where('project_id', $projectId);
                break;

            case Report::TARGET_FREELANCER:
            case Report::TARGET_COMPANY:
                // Jika dari workspace, kunci = workspace (company vs freelancer
                // pada workspace yang sama). Jika dari penawaran, kunci =
                // reported_user (freelancer yang dilaporkan).
                if ($workspaceId) {
                    $query->where('workspace_id', $workspaceId);
                } else {
                    $query->where('reported_user_id', $reportedId);
                }
                break;

case Report::TARGET_WEBSITE:
            default:
                // General / Website report: ONE active Website report per reporter.
                // Kunci duplikat = reporter_id + target=website (status aktif).
                // Mengganti category/subject/description/attachment TIDAK boleh
                // menembus anti-spam. Setelah status selesai/ditolak, laporan
                // baru diizinkan (karena query hanya memfilter status ACTIVE).
                $query->where('target', Report::TARGET_WEBSITE);
                break;
        }

        if ($query->lockForUpdate()->exists()) {
            throw ValidationException::withMessages([
                'subject' => 'Laporan serupa untuk target ini masih dalam proses peninjauan oleh Admin. '
                    . 'Anda tidak dapat mengirim laporan yang sama sebelum laporan sebelumnya selesai diproses.',
            ]);
        }
    }

    /**
     * Update status laporan oleh admin (workflow 5 status V3).
     *
     * @param  Report  $report
     * @param  string  $status     ditinjau|menunggu-bukti|selesai|ditolak
     * @param  string|null  $adminNote
     * @param  int|null  $handledBy
     * @return Report
     */
    public function updateStatus(Report $report, string $status, ?string $adminNote = null, ?int $handledBy = null): Report
    {
        DB::transaction(function () use ($report, $status, $adminNote, $handledBy) {
            $data = [
                'status'     => $status,
                'admin_note' => $adminNote ?? $report->admin_note,
            ];

            if ($handledBy && !$report->handled_by) {
                $data['handled_by'] = $handledBy;
            }

            if (in_array($status, Report::RESOLVED_STATUSES, true)) {
                $data['resolved_at'] = now();
            } else {
                $data['resolved_at'] = null;
            }

            $report->update($data);

            // Beri tahu pelapor tentang perubahan status.
            $this->notifyReporterStatus($report, $status, $adminNote);
        });

        return $report->fresh();
    }

    /**
     * Simpan lampiran report ke storage 'public' & tabel report_attachments.
     */
    protected function storeAttachments(Report $report, ?array $files): void
    {
        if (empty($files)) {
            return;
        }

        foreach ($files as $file) {
            if (!$file) {
                continue;
            }

            $path = $file->store('report-attachments', 'public');
            $mime = $file->getMimeType();

            ReportAttachment::create([
                'report_id'  => $report->id,
                'file_name'  => $file->getClientOriginalName(),
                'file_path'  => $path,
                'disk'       => 'public',
                'mime_type'  => $mime,
                'file_size'  => $file->getSize(),
                'type'       => str_starts_with((string) $mime, 'image/') ? 'image' : 'file',
            ]);
        }
    }

    /**
     * Kirim notifikasi ke semua admin.
     */
    public function notifyAdmins(Report $report, ?string $customTitle = null): void
    {
        $admins = User::where('role', 'admin')->get();
        $title  = $customTitle ?? 'Laporan Baru: ' . $report->subject;

        foreach ($admins as $admin) {
            NotificationService::sendTo(
                user:      $admin->id,
                type:      'report.created',
                title:     $title,
                message:   'Laporan "' . $report->subject . '" (target: ' .
                           Report::targetLabel($report->target) . ', kategori: ' .
                           Report::categoryLabel($report->category) . ') menunggu peninjauan.',
                redirect:  route('admin.reports.show', $report),
                senderId:  Auth::id(),
                projectId: $report->project_id,
                metadata:  ['report_id' => $report->id],
            );
        }
    }

    /**
     * Beri tahu pelapor ketika admin mengubah status laporan.
     */
    protected function notifyReporterStatus(Report $report, string $status, ?string $adminNote): void
    {
        $statusLabel = Report::statusLabel($status);
        $message = 'Laporan "' . $report->subject . '" kini berstatus: ' . $statusLabel . '.';

        if ($adminNote) {
            $message .= ' Catatan Admin: ' . $adminNote;
        }

        // Redirect reporter ke halaman detail laporan mereka sesuai role.
        $redirect = $report->reporter
            ? ($report->reporter->role === 'company'
                ? route('company.reports.show', $report)
                : route('freelancer.reports.show', $report))
            : null;

        NotificationService::sendTo(
            user:    $report->reporter_id,
            type:    'report.status_updated',
            title:   'Status Laporan Diperbarui',
            message: $message,
            redirect: $redirect,
            senderId: Auth::id(),
            projectId: $report->project_id,
            metadata: ['report_id' => $report->id],
        );
    }
}
