<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReportResolutionRequest;
use App\Http\Requests\Admin\ReportUpdateStatusRequest;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Report;
use App\Services\EscrowService;
use App\Services\NotificationService;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    protected $reportService;

    protected $escrowService;

    public function __construct(ReportService $reportService, EscrowService $escrowService)
    {
        $this->reportService = $reportService;
        $this->escrowService = $escrowService;
    }

    public function index(Request $request): View
    {
$query = Report::with([
            'reporter',
            'project',
            'reportedUser',
            'workspace',
        ]);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($target = $request->input('target')) {
            $query->where('target', $target);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('reporter', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

public function show(Report $report): View
    {
        $report->load([
            'reporter',
            'reportedUser',
            'project.owner',
            'penawaran.freelancer',
            'workspace.project',
            'workspace.payment',
            'payment',
            'handledBy',
            'attachments',
        ]);

        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(ReportUpdateStatusRequest $request, Report $report): RedirectResponse
    {
        $this->reportService->updateStatus(
            $report,
            $request->validated('status'),
            $request->validated('admin_note'),
            Auth::id()
        );

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Hapus project yang terkait dengan laporan (Kasus 1 - Freelancer lapor Company).
     */
    public function destroyProject(Request $request, Report $report): RedirectResponse
    {
        $project = $report->project;

        if (!$project) {
            return redirect()
                ->route('admin.reports.show', $report)
                ->with('error', 'Data project sudah tidak tersedia.');
        }

        DB::transaction(function () use ($project, $report) {
            $report->update(['project_id' => null]);
            $project->delete();
        });

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Project berhasil dihapus.');
    }

    /**
     * Hapus penawaran yang terkait dengan laporan (Kasus 2 - Company lapor Freelancer).
     */
    public function destroyPenawaran(Request $request, Report $report): RedirectResponse
    {
        $penawaran = $report->penawaran;

        if (!$penawaran) {
            return redirect()
                ->route('admin.reports.show', $report)
                ->with('error', 'Data penawaran sudah tidak tersedia.');
        }

        DB::transaction(function () use ($penawaran, $report) {
            $report->update(['penawaran_id' => null]);
            $penawaran->delete();
        });

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Penawaran berhasil dihapus.');
    }

    /**
     * Terima Laporan Keterlambatan: laporan dinyatakan VALID oleh Admin.
     *
     * - Status laporan menjadi 'ditangani' (Ditangani).
     * - Freelancer menerima peringatan resmi; company menerima notifikasi.
     * - Project/workspace TETAP berjalan (tidak dibatalkan/dihapus).
     * - Dana escrow TIDAK disentuh (no release/refund/split).
     */
    public function acceptReport(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $this->reportService->acceptKeterlambatan(
                $report,
                $validated['admin_note'] ?? null,
                Auth::id()
            );
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('admin.reports.show', $report)
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Gagal menerima laporan keterlambatan #' . $report->id . ': ' . $e->getMessage());

            return redirect()
                ->route('admin.reports.show', $report)
                ->with('error', 'Terjadi kesalahan saat menerima laporan keterlambatan.');
        }

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Laporan keterlambatan diterima. Freelancer menerima peringatan resmi dan company telah diberi notifikasi. Proyek tetap berjalan.');
    }

    // ─────────────────────────────────────────────────────────────
    // DISPUTE RESOLUTION — hanya Admin, backend wajib authorisasi
    // (rute berada dalam grup middleware ensureAdmin).
    // ─────────────────────────────────────────────────────────────

    /**
     * Keputusan Admin: Release penuh dana held ke freelancer.
     */
    public function releaseFunds(ReportResolutionRequest $request, Report $report): RedirectResponse
    {
        return $this->resolveFunds($request, $report, 'release');
    }

    /**
     * Keputusan Admin: Refund penuh dana held ke company.
     */
    public function refundFunds(ReportResolutionRequest $request, Report $report): RedirectResponse
    {
        return $this->resolveFunds($request, $report, 'refund');
    }

    /**
     * Keputusan Admin: Split/partial — freelancer menerima $freelancer_amount,
     * sisa dana (freelancer_receive - $freelancer_amount) dikembalikan ke company.
     */
    public function splitFunds(ReportResolutionRequest $request, Report $report): RedirectResponse
    {
        return $this->resolveFunds($request, $report, 'split');
    }

    private function resolveFunds(ReportResolutionRequest $request, Report $report, string $action): RedirectResponse
    {
        if (in_array($report->status, [Report::STATUS_SELESAI, Report::STATUS_DITOLAK], true)) {
            return redirect()
                ->route('admin.reports.show', $report)
                ->with('error', 'Laporan ini sudah ditutup, tidak dapat memproses dana lagi.');
        }

        $payment = $this->resolvePayment($report);

        try {
            DB::transaction(function () use ($request, $report, $payment, $action) {
                $adminId = Auth::id();
                $note = $request->validated('admin_note');

                if ($action === 'release') {
                    $this->escrowService->release($payment, $report, null, $adminId);
                } elseif ($action === 'refund') {
                    $this->escrowService->refund($payment, $report, null, $adminId);
                } else { // split
                    $freelancerAmount = (float) $request->validated('freelancer_amount', 0);
                    $refundAmount = round((float) $payment->freelancer_receive - $freelancerAmount, 2);

                    $this->escrowService->partialRelease($payment, $freelancerAmount, $refundAmount, $report, null, $adminId);
                }

                // Laporan ditutup + catatan admin (audit trail)
                $report->update([
                    'status' => Report::STATUS_SELESAI,
                    'admin_note' => $note,
                    'handled_by' => $adminId,
                    'resolved_at' => now(),
                ]);

                // System message di workspace + notifikasi ke company & freelancer
                $this->notifyResolution($payment, $report, $action, $note);
            });
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('admin.reports.show', $report)
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Gagal resolusi dana report #' . $report->id . ': ' . $e->getMessage());

            return redirect()
                ->route('admin.reports.show', $report)
                ->with('error', 'Terjadi kesalahan saat memproses dana dispute.');
        }

        return redirect()
            ->route('admin.reports.show', $report)
            ->with('success', 'Keputusan dana dispute berhasil diproses dan tercatat di ledger.');
    }

    private function resolvePayment(Report $report): Payment
    {
        $payment = $report->payment ?? $report->workspace?->payment;

        if (!$payment) {
            abort(422, 'Tidak ada pembayaran terkait pada laporan ini.');
        }

        return $payment;
    }

    private function notifyResolution(Payment $payment, Report $report, string $action, string $note): void
    {
        $workspace = $payment->workspace;
        $projectName = $workspace?->project->project_name ?? '';

        $labels = [
            'release' => 'Dana Dirilis ke Freelancer',
            'refund'  => 'Dana Direfund ke Company',
            'split'   => 'Dana Dibagi (Split)',
        ];
        $label = $labels[$action] ?? 'Dana Diresolusi';

        if ($workspace) {
            Message::create([
                'workspace_id' => $workspace->id,
                'sender_id' => Auth::id(),
                'message' => 'Admin menyelesaikan dispute: ' . $label . ".\n\nAlasan:\n" . $note,
                'type' => 'system',
            ]);
        }

        $baseMessage = 'Keputusan Admin untuk laporan "' . ($report->subject ?? '') . '" pada proyek "'
            . $projectName . '": ' . $label . ".\n\nCatatan:\n" . $note;

        NotificationService::sendTo(
            user: $payment->freelancer_id,
            type: 'funds.resolved',
            title: $label,
            message: $baseMessage,
            redirect: $workspace ? route('freelancer.workspaces.show', $workspace) : null,
            senderId: Auth::id(),
            paymentId: $payment->id,
            workspaceId: $workspace?->id,
            projectId: $workspace?->project_id,
        );

        NotificationService::sendTo(
            user: $payment->company_id,
            type: 'funds.resolved',
            title: $label,
            message: $baseMessage,
            redirect: $workspace ? route('company.workspaces.show', $workspace) : null,
            senderId: Auth::id(),
            paymentId: $payment->id,
            workspaceId: $workspace?->id,
            projectId: $workspace?->project_id,
        );
    }
}
