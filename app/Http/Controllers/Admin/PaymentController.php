<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Message;
use App\Models\Workspace;
use App\Services\EscrowService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    /**
     * Display a listing of all payments.
     */
    public function index(Request $request): View
{
    $query = Payment::with([
        'workspace.project',
        'company',
        'freelancer',
    ])->latest();

    if ($request->filled('status')) {

        switch ($request->status) {

            case 'paid':
                $query->where('status', 'paid');
                break;

            case 'waiting_verification':
                $query->where('status', 'waiting_verification');
                break;

            case 'rejected':
                $query->where('status', 'rejected');
                break;
        }
    }

    $payments = $query->paginate(15)->withQueryString();

    return view('admin.payments.index', compact('payments'));
}
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
    public function verify(
        Request $request,
        Payment $payment
    ): RedirectResponse {
        // Pastikan status payment adalah waiting_verification
       
        if (!in_array($payment->status, ['waiting_verification', 'pending'])) {
    return redirect()
        ->route('admin.payments.show', $payment)
        ->with('error', 'Status pembayaran tidak dalam status menunggu verifikasi.');
}

        $workspace = $payment->workspace;

        // Update payment + tahan dana (escrow) + unlock workspace dalam SATU transaction
        try {
            DB::transaction(function () use ($payment, $workspace) {
                // Update payment
                $payment->update([
                    'status' => 'paid',
                    'verified_by' => Auth::id(),
                    'verified_at' => now(),
                ]);

// Dana otomatis DITAHAN (escrow) — belum menjadi pendapatan freelancer.
                app(EscrowService::class)->hold($payment, null, Auth::id());

                // Update workspace status menjadi Sedang Dikerjakan
                $workspace->update(['status' => 'Sedang Dikerjakan']);
            });
        } catch (\Exception $e) {
            Log::error('Gagal verifikasi payment #' . $payment->id . ': ' . $e->getMessage());

            return redirect()
                ->route('admin.payments.show', $payment)
                ->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }

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
message: 'Pembayaran untuk proyek "' . ($workspace->project->project_name ?? '') . '" telah diverifikasi. Dana sebesar Rp ' . number_format($payment->freelancer_receive, 0, ',', '.') . ' sedang ditahan (escrow) dan akan dirilis ke Anda setelah proyek selesai.',
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
message: 'Pembayaran untuk proyek "' . ($workspace->project->project_name ?? '') . '" berhasil diverifikasi oleh Admin. Status workspace telah menjadi Sedang Dikerjakan.',
            redirect: route('company.workspaces.show', $workspace),
            senderId: Auth::id(),
            paymentId: $payment->id,
            workspaceId: $workspace->id,
        );

        return redirect()
            ->route('admin.payments.show', $payment)
->with('success', 'Pembayaran berhasil diverifikasi. Dana otomatis ditahan (escrow) dan workspace telah menjadi Sedang Dikerjakan.');
    }

    /**
     * Reject payment.
     */
    public function reject(
        Request $request,
        Payment $payment
    ): RedirectResponse {
        // Pastikan status payment adalah waiting_verification
      if (!in_array($payment->status, ['waiting_verification', 'pending'])) {
    return redirect()
        ->route('admin.payments.show', $payment)
        ->with('error', 'Status pembayaran tidak dalam status menunggu verifikasi.');
}

        $request->validate([
            'admin_note' => [
                'nullable',
                'string',
                'max:2000',
            ],
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
        $workspace->update([
            'status' => 'Menunggu Pembayaran',
        ]);

        // System message
        $messageText = 'Pembayaran ditolak Admin.';

        if ($request->filled('admin_note')) {
            $messageText .=
                "\n\nAlasan:\n" .
                $request->admin_note;
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
            message:
                'Pembayaran untuk proyek "' .
                ($workspace->project->project_name ?? '') .
                '" ditolak oleh Admin. Silakan upload ulang bukti pembayaran.' .
                (
                    $request->filled('admin_note')
                        ? "\n\nAlasan: " . $request->admin_note
                        : ''
                ),
            redirect: route(
                'company.workspaces.show',
                $workspace
            ),
            senderId: Auth::id(),
            paymentId: $payment->id,
            workspaceId: $workspace->id,
        );

        // Notification for freelancer
        NotificationService::sendTo(
            user: $payment->freelancer_id,
            type: 'payment.rejected',
            title: 'Pembayaran Ditolak',
            message:
                'Pembayaran untuk proyek "' .
                ($workspace->project->project_name ?? '') .
                '" ditolak oleh Admin. Menunggu perusahaan mengupload ulang bukti pembayaran.',
            redirect: route(
                'freelancer.workspaces.show',
                $workspace
            ),
            senderId: Auth::id(),
            paymentId: $payment->id,
            workspaceId: $workspace->id,
        );

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with(
                'success',
                'Pembayaran ditolak. Perusahaan dapat mengupload ulang bukti pembayaran.'
            );
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

        /*
        |--------------------------------------------------------------------------
        | KEAMANAN CETAK STRUK
        |--------------------------------------------------------------------------
        | Struk satuan hanya boleh dicetak jika pembayaran sudah dibayar.
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            strtolower($payment->status),
            [
                'paid',
                'dibayar',
                'selesai',
            ]
        )) {
            return redirect()
                ->route('admin.payments.index')
                ->with(
                    'error',
                    'Struk hanya dapat dicetak untuk transaksi yang sudah dibayar.'
                );
        }

        if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = Pdf::loadView(
                'admin.payments.pdf_single',
                compact('payment')
            );

            return $pdf->download(
                'struk-pembayaran-' .
                $payment->id .
                '.pdf'
            );
        }

        return view(
            'admin.payments.pdf_single',
            compact('payment')
        );
    }

    /**
     * Export laporan pembayaran ke PDF berdasarkan filter status.
     */
    public function exportPdfAll(Request $request)
    {
        $query = Payment::with([
            'workspace.project',
            'company',
            'freelancer',
        ]);

        /*
        |--------------------------------------------------------------------------
        | FILTER PDF
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $status = strtolower($request->status);

            // Dibayar
            if ($status === 'paid') {
                $query->whereIn(
                    DB::raw('LOWER(status)'),
                    [
                        'paid',
                        'dibayar',
                        'selesai',
                    ]
                );
            }

            // Pending
            elseif ($status === 'pending') {
                $query->whereIn(
                    DB::raw('LOWER(status)'),
                    [
                        'pending',
                        'waiting_verification',
                    ]
                );
            }

            // Ditolak
            elseif ($status === 'rejected') {
                $query->whereIn(
                    DB::raw('LOWER(status)'),
                    [
                        'rejected',
                        'ditolak',
                    ]
                );
            }
        }

        $payments = $query
            ->latest()
            ->get();

        $filename =
            'laporan-pembayaran-' .
            ($request->status ?? 'semua') .
            '.pdf';

        /*
        |--------------------------------------------------------------------------
        | GENERATE PDF ASLI
        |--------------------------------------------------------------------------
        */

        if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = Pdf::loadView(
                'admin.payments.pdf-all',
                compact('payments')
            );

            return $pdf->download($filename);
        }

        return view(
            'admin.payments.pdf-all',
            compact('payments')
        );
    }
}