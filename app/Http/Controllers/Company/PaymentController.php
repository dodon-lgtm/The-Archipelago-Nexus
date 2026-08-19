<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Workspace;
use App\Models\Message;
use App\Models\User;
use App\Services\EscrowService;
use App\Services\NotificationService;
use App\Services\ProfileCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
/**
     * Show the Payment Gateway simulation page.
     */
    public function showGateway(Workspace $workspace)
    {
        // Hanya company yang bisa akses
        if ((int) $workspace->company_id !== (int) Auth::id()) {
            abort(403, 'Hanya perusahaan yang dapat mengakses halaman ini.');
        }

        $payment = $workspace->payment;

        if (!$payment) {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Halaman Payment Gate menjadi gerbang pembayaran resmi.
        // Seluruh status payment (pending / waiting_verification / paid / rejected)
        // ditangani langsung di dalam view gateway.
        return view('company.payments.gateway', compact('workspace', 'payment'));
    }

    /**
     * Show the payment upload form (or redirect if not applicable).
     */
    public function showUploadForm(Workspace $workspace)
    {
        // Hanya company yang bisa akses
        if ((int) $workspace->company_id !== (int) Auth::id()) {
            abort(403, 'Hanya perusahaan yang dapat mengakses halaman ini.');
        }

        // Pastikan status workspace adalah Menunggu Pembayaran
        if ($workspace->status !== 'Menunggu Pembayaran') {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Workspace tidak dalam status Menunggu Pembayaran.');
        }

        $payment = $workspace->payment;

        if (!$payment) {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        return view('company.payments.upload', compact('workspace', 'payment'));
    }

    /**
     * Upload payment proof.
     */
    public function uploadProof(Request $request, Workspace $workspace): RedirectResponse
    {
        // Hanya company yang bisa upload
        if ((int) $workspace->company_id !== (int) Auth::id()) {
            abort(403, 'Hanya perusahaan yang dapat mengupload bukti pembayaran.');
        }

        // Cek kelengkapan profil company
        $completionService = app(ProfileCompletionService::class);
        if (!$completionService->isComplete(Auth::user())) {
            return redirect()
                ->route('company.profile')
                ->with('error', 'Profil Anda belum lengkap. Silakan lengkapi minimal 80% profil terlebih dahulu agar dapat mengupload bukti pembayaran.');
        }

        // Pastikan workspace dalam status Menunggu Pembayaran
        if ($workspace->status !== 'Menunggu Pembayaran') {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Workspace tidak dalam status Menunggu Pembayaran.');
        }

        $payment = $workspace->payment;

        if (!$payment) {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Jika payment sudah paid, tidak bisa upload lagi
        if ($payment->status === 'paid') {
            return redirect()
                ->route('company.workspaces.show', $workspace)
                ->with('error', 'Pembayaran sudah diverifikasi.');
        }

        $request->validate([
            'payment_method' => ['required', 'string', 'in:Transfer Bank,QRIS,E-Wallet'],
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'], // 10 MB
            'company_note' => ['nullable', 'string', 'max:2000'],
        ], [
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_proof.required' => 'Bukti pembayaran wajib diupload.',
            'payment_proof.mimes' => 'Bukti pembayaran harus berupa file: jpg, jpeg, png, atau pdf.',
            'payment_proof.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        // Hapus file bukti pembayaran lama jika ada (untuk re-upload)
        if ($payment->payment_proof) {
            Storage::disk('public')->delete($payment->payment_proof);
        }

        // Upload file bukti pembayaran
        $filePath = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        // Update payment
        $payment->update([
            'payment_method' => $request->payment_method,
            'payment_proof' => $filePath,
            'company_note' => $request->company_note,
            'status' => 'waiting_verification',
        ]);

        // Update workspace status
        $workspace->update(['status' => 'Menunggu Verifikasi Admin']);

        // System message
        Message::create([
            'workspace_id' => $workspace->id,
            'sender_id' => Auth::id(),
            'message' => 'Perusahaan telah mengirim bukti pembayaran dan menunggu verifikasi admin.',
            'type' => 'system',
        ]);

        // Notifikasi untuk semua admin (pembayaran perlu diverifikasi)
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationService::sendTo(
                user: $admin->id,
                type: 'payment.waiting',
                title: 'Pembayaran Perlu Diverifikasi',
                message: 'Perusahaan "' . (Auth::user()->companyProfile->company_name ?? Auth::user()->name) . '" telah mengirim bukti pembayaran untuk proyek "' . ($workspace->project->project_name ?? '') . '". Silakan lakukan verifikasi.',
                redirect: route('admin.payments.show', $payment),
                senderId: Auth::id(),
                paymentId: $payment->id,
                workspaceId: $workspace->id,
                projectId: $workspace->project_id,
            );
        }

        return redirect()
            ->route('company.workspaces.show', $workspace)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }

    /**
     * Create Midtrans Snap Token for automated payment.
     */
    public function createMidtransTransaction(Request $request, Workspace $workspace)
    {
        // 1. Authorization: Pastikan company yang login adalah pemilik workspace
        if ((int) $workspace->company_id !== (int) Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya perusahaan pemilik workspace yang dapat melakukan pembayaran.'
            ], 403);
        }

        // 2. Cek status workspace
        if ($workspace->status !== 'Menunggu Pembayaran') {
            return response()->json([
                'success' => false,
                'message' => 'Workspace tidak dalam status Menunggu Pembayaran.'
            ], 422);
        }

        // 3. Ambil record Payment
        $payment = $workspace->payment;
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan.'
            ], 404);
        }

        // 4. Pastikan pembayaran belum paid
        if ($payment->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah lunas / diverifikasi.'
            ], 422);
        }

        // 5. Validasi nominal amount dari database
        if (!$payment->amount || (float) $payment->amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Nominal pembayaran tidak valid.'
            ], 422);
        }

        // 6. Buat Snap Token menggunakan MidtransService
        try {
            $midtransService = app(\App\Services\MidtransService::class);

            // Order ID unik per-attempt; dicatat agar webhook bisa memvalidasi & audit trail lengkap.
            $orderId = $midtransService->buildOrderId($payment);

            \App\Models\MidtransAttempt::create([
                'payment_id' => $payment->id,
                'order_id' => $orderId,
                'status' => 'created',
            ]);

            $snapToken = $midtransService->createSnapToken($payment, $workspace, $orderId);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans Snap Token Error [Workspace #' . $workspace->id . ', Payment #' . $payment->id . ']: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat token pembayaran Midtrans. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * ── TEMPORARY / TESTING FLOW ──────────────────────────────────────────
     * Konfirmasi pembayaran dari sisi backend (tanpa menunggu webhook Midtrans).
     *
     * PERINGATAN: Ini adalah FLOW SEMENTARA untuk TESTING saja.
     * Hanya aktif bila config('services.midtrans.temporary_confirmation') = true
     * (env PAYMENT_TEMPORARY_CONFIRMATION=true).
     *
     * Setelah webhook Midtrans diperbaiki, matikan flag ini dan kembalikan
     * kepercayaan konfirmasi pembayaran ke webhook resmi Midtrans.
     *
     * Keamanan:
     * - Wajib authenticated (dijamin oleh route group 'auth').
     * - Hanya Company pemilik workspace.
     * - Amount diambil dari database ($payment->amount), TIDAK dari browser.
     * - Tidak menerima company_id / freelancer_id / status dari browser.
     * - Idempotent: payment yang sudah paid tidak diproses ulang.
     * - Atomic: payment paid + escrow hold + workspace unlock dalam satu transaction.
     */
    public function confirmPayment(Request $request, Workspace $workspace)
    {
        // 1. Flag temporary flow harus aktif
        if (!(bool) config('services.midtrans.temporary_confirmation', false)) {
            abort(404, 'Temporary payment confirmation tidak aktif.');
        }

        // 2. Authorization: hanya Company pemilik workspace
        if ((int) $workspace->company_id !== (int) Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya perusahaan pemilik workspace yang dapat mengonfirmasi pembayaran.'
            ], 403);
        }

        // 3. Verifikasi payment terkait workspace
        $payment = $workspace->payment;
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan.'
            ], 404);
        }

        // 4. Pastikan payment belum paid (idempotent)
        if ($payment->status === 'paid') {
            Log::info('Temporary payment confirmation: sudah paid, tidak diproses ulang', [
                'workspace_id' => $workspace->id,
                'payment_id' => $payment->id,
                'invoice_number' => $payment->invoice_number,
            ]);

            return response()->json([
                'success' => true,
                'redirect_url' => route('company.workspaces.show', $workspace),
                'message' => 'Pembayaran sudah selesai sebelumnya.',
            ]);
        }

        // 5. Jangan izinkan konfirmasi pada workspace yang sudah selesai/diproses
        if (in_array($workspace->status, ['Selesai', 'Menunggu Review'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Workspace sudah berada dalam status yang tidak dapat dikonfirmasi pembayaran.'
            ], 422);
        }

        Log::info('Temporary payment confirmation started', [
            'workspace_id' => $workspace->id,
            'payment_id' => $payment->id,
            'invoice_number' => $payment->invoice_number,
            'old_payment_status' => $payment->status,
            'old_workspace_status' => $workspace->status,
        ]);

        // 6. Proses atomik: paid -> escrow held -> workspace dibuka
        try {
            DB::transaction(function () use ($payment, $workspace) {
                // Payment menjadi paid (amount dari database)
                $payment->update([
                    'status' => 'paid',
                    'payment_method' => $payment->payment_method ?? 'Midtrans',
                    'verified_at' => $payment->verified_at ?? now(),
                ]);

                // Escrow: dana ditahan oleh platform/admin (bukan langsung ke freelancer)
                app(EscrowService::class)->hold(
                    $payment,
                    'Dana ditahan (escrow) setelah pembayaran dikonfirmasi (temporary flow).',
                    Auth::id()
                );

                // Workspace dibuka
                $workspace->update(['status' => 'Sedang Dikerjakan']);
            });
        } catch (\Exception $e) {
            Log::error('Temporary payment confirmation failed', [
                'workspace_id' => $workspace->id,
                'payment_id' => $payment->id,
                'invoice_number' => $payment->invoice_number,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengonfirmasi pembayaran: ' . $e->getMessage(),
            ], 500);
        }

        Log::info('Temporary payment confirmation completed', [
            'workspace_id' => $workspace->id,
            'payment_id' => $payment->id,
            'invoice_number' => $payment->invoice_number,
            'new_payment_status' => $payment->fresh()->status,
            'new_workspace_status' => $workspace->fresh()->status,
        ]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('company.workspaces.show', $workspace),
            'message' => 'Pembayaran berhasil dikonfirmasi. Workspace telah dibuka.',
        ]);
    }
}
