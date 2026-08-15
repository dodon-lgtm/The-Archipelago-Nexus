<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Message;
use App\Models\Workspace;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    /**
     * Display a listing of all payments.
     */
    public function index(): View
    {
        $payments = Payment::with([
            'workspace.project',
            'company',
            'freelancer',
        ])
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment): View
    {
        $payment->load([
            'workspace.project',
            'company',
            'freelancer',
            'verifier',
        ]);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Verify payment (approve).
     */
    public function verify(Request $request, Payment $payment): RedirectResponse
    {
        // Pastikan status payment adalah waiting_verification
        if ($payment->status !== 'waiting_verification') {
            return redirect()
                ->route('admin.payments.show', $payment)
                ->with('error', 'Status pembayaran tidak dalam status menunggu verifikasi.');
        }

        $workspace = $payment->workspace;

        // Update payment
        $payment->update([
            'status' => 'paid',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Update workspace status menjadi Selesai
        $workspace->update(['status' => 'Selesai']);

        // System message
        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => Auth::id(),
            'message' => 'Pembayaran telah diverifikasi oleh Admin.',
            'type' => 'system',
        ]);

        // Notification for freelancer
        NotificationService::sendTo(
            user: $payment->freelancer_id,
            type: 'payment.verified',
            title: 'Pembayaran Diverifikasi',
            message: 'Pembayaran untuk proyek "' . ($workspace->project->project_name ?? '') . '" telah diverifikasi. Saldo sebesar Rp ' . number_format($payment->freelancer_receive, 0, ',', '.') . ' telah diterima.',
            redirect: route('freelancer.workspaces.show', $workspace),
            senderId: Auth::id(),
            paymentId: $payment->id,
            workspaceId: $workspace->id,
        );

        // Notification for company
        NotificationService::sendTo(
            user: $payment->company_id,
            type: 'payment.verified',
            title: 'Pembayaran Diverifikasi',
            message: 'Pembayaran untuk proyek "' . ($workspace->project->project_name ?? '') . '" berhasil diverifikasi oleh Admin. Status workspace telah menjadi Selesai.',
            redirect: route('company.workspaces.show', $workspace),
            senderId: Auth::id(),
            paymentId: $payment->id,
            workspaceId: $workspace->id,
        );

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil diverifikasi. Status workspace telah menjadi Selesai.');
    }

    /**
     * Reject payment.
     */
    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        // Pastikan status payment adalah waiting_verification
        if ($payment->status !== 'waiting_verification') {
            return redirect()
                ->route('admin.payments.show', $payment)
                ->with('error', 'Status pembayaran tidak dalam status menunggu verifikasi.');
        }

        $request->validate([
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $workspace = $payment->workspace;

        // Update payment
        $payment->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Update workspace status kembali ke Menunggu Pembayaran
        $workspace->update(['status' => 'Menunggu Pembayaran']);

        // System message
        $messageText = 'Pembayaran ditolak Admin.';
        if ($request->filled('admin_note')) {
            $messageText .= "\n\nAlasan:\n" . $request->admin_note;
        }

        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => Auth::id(),
            'message' => $messageText,
            'type' => 'system',
        ]);

        // Notification for company
        NotificationService::sendTo(
            user: $payment->company_id,
            type: 'payment.rejected',
            title: 'Pembayaran Ditolak',
            message: 'Pembayaran untuk proyek "' . ($workspace->project->project_name ?? '') . '" ditolak oleh Admin. Silakan upload ulang bukti pembayaran.' . ($request->filled('admin_note') ? "\n\nAlasan: " . $request->admin_note : ''),
            redirect: route('company.workspaces.show', $workspace),
            senderId: Auth::id(),
            paymentId: $payment->id,
            workspaceId: $workspace->id,
        );

        // Notification for freelancer
        NotificationService::sendTo(
            user: $payment->freelancer_id,
            type: 'payment.rejected',
            title: 'Pembayaran Ditolak',
            message: 'Pembayaran untuk proyek "' . ($workspace->project->project_name ?? '') . '" ditolak oleh Admin. Menunggu perusahaan mengupload ulang bukti pembayaran.',
            redirect: route('freelancer.workspaces.show', $workspace),
            senderId: Auth::id(),
            paymentId: $payment->id,
            workspaceId: $workspace->id,
        );

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with('success', 'Pembayaran ditolak. Perusahaan dapat mengupload ulang bukti pembayaran.');
    }

    /**
     * Export / Cetak Struk Pembayaran Tunggal.
     */
    public function exportPdfSingle($id)
    {
        $payment = Payment::with([
            'workspace.project',
            'company',
            'freelancer',
            'verifier',
        ])->findOrFail($id);

        if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = Pdf::loadView('admin.payments.pdf_single', compact('payment'));
            return $pdf->download('struk-pembayaran-' . $payment->id . '.pdf');
        }

        return view('admin.payments.pdf_single', compact('payment'));
    }

    /**
     * Export seluruh laporan pembayaran ke PDF / View.
     */
    public function exportPdfAll()
    {
        $payments = Payment::with([
            'workspace.project',
            'company',
            'freelancer',
        ])->latest()->get();

        if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
           // Ubah admin.payments.pdf_all menjadi admin.payments.pdf-all
$pdf = Pdf::loadView('admin.payments.pdf-all', compact('payments'));

return view('admin.payments.pdf-all', compact('payments'));
        }

        return view('admin.payments.pdf_all', compact('payments'));
    }
}