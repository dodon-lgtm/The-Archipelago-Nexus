<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Workspace;
use App\Models\Message;
use App\Models\User;
use App\Services\AdminWalletService;
use App\Services\EscrowService;
use App\Services\NotificationService;
use App\Services\ProfileCompletionService;
use App\Services\ProjectQuotaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

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
        $workspace->load(['project', 'freelancer', 'company']);

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

    /* ─── QUOTA PAYMENT (Project Slot — Rp10.000/slot via Midtrans) ─────
    | Dipakai ulang sistem Payment + Midtrans + wallet_ledger yang existing.
    | Tidak ada workspace / freelancer / escrow — hanya catat income ke Admin Wallet.
    */

    /**
     * Info kuota bulan berjalan untuk company yang login.
     */
    public function quotaPaymentInfo(Request $request)
    {
        $userId = Auth::id();

        $quotaService = new ProjectQuotaService();

        return response()->json([
            'can_create'      => $quotaService->canCreateProject($userId),
            'used_slots'      => $quotaService->usedSlots($userId),
            'available_slots' => $quotaService->availableSlots($userId),
            'free_quota'      => $quotaService->freeQuota(),
            'paid_slots'      => $quotaService->paidSlotsThisMonth($userId),
        ]);
    }

    /**
     * Pastikan ada TEPAT SATU Payment kuota aktif (pending/waiting_verification)
     * untuk company. Idempotent — reuse payment pending yang ada JIKA amount-nya
     * SESUAI dengan Financial Settings saat ini. Jika price berubah, buat payment
     * BARU dengan amount terbaru (payment lama tetap immutable).
     */
    public static function ensurePendingQuotaPayment(int $userId): Payment
    {
        return DB::transaction(function () use ($userId) {
            $currentPrice = AdminWalletService::quotaPrice();

            $existing = Payment::where('company_id', $userId)
                ->where('payment_type', Payment::PAYMENT_TYPE_QUOTA)
                ->whereIn('status', ['pending', 'waiting_verification'])
                ->latest('id')
                ->first();

            if ($existing && (float) $existing->amount === (float) $currentPrice) {
                return $existing;
            }

            // Price berubah atau tidak ada pending payment → buat payment BARU.
            // Payment lama (amount berbeda) tetap immutable di DB.
            $seq = (int) (Payment::max('id') ?? 0) + 1;
            $invoiceNumber = 'INV-QOT-' . now()->format('Ymd') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

            return Payment::create([
                'company_id'     => $userId,
                'freelancer_id'  => null,
                'workspace_id'   => null,
                'invoice_number' => $invoiceNumber,
                'amount'         => $currentPrice,
                'payment_type'   => Payment::PAYMENT_TYPE_QUOTA,
                'status'         => 'pending',
                'payment_method' => 'Midtrans',
            ]);
        });
    }

    /**
     * Titik masuk tombol "Bayar Rp10.000" pada modal kuota: pastikan payment
     * pending ada, lalu arahkan ke halaman GATEWAY pembayaran kuota.
     * TIDAK melakukan pembayaran otomatis di sini.
     */
    public function startQuotaPayment(): RedirectResponse
    {
        $payment = static::ensurePendingQuotaPayment((int) Auth::id());

        return redirect()->route('company.quota.payment.show', $payment);
    }

    /**
     * Halaman GATEWAY pembayaran kuota — konsisten dengan gateway pembayaran
     * proyek (company/payments/gateway.blade.php).
     */
    public function showQuotaGateway(Payment $payment): View
    {
        // Ownership: hanya company pemilik payment & hanya type quota.
        abort_unless(
            (int) $payment->company_id === (int) Auth::id()
                && $payment->payment_type === Payment::PAYMENT_TYPE_QUOTA,
            403
        );

        $quotaService = new ProjectQuotaService();

        return view('company.payments.quota-gateway', [
            'payment' => $payment,
            'quota'   => [
                'used_slots'      => $quotaService->usedSlots(Auth::id()),
                'available_slots' => $quotaService->availableSlots(Auth::id()),
                'free_quota'      => $quotaService->freeQuota(),
                'paid_slots'      => $quotaService->paidSlotsThisMonth(Auth::id()),
                'can_create'      => $quotaService->canCreateProject(Auth::id()),
            ],
            'price'   => (int) round($payment->amount), // server-side truth
        ]);
    }

    /**
     * Buat Snap token Midtrans untuk payment kuota TERTENTU.
     * Order ID unik per attempt; amount dari database (bukan browser).
     */
    public function createQuotaTransaction(Request $request, Payment $payment)
    {
        abort_unless(
            (int) $payment->company_id === (int) Auth::id()
                && $payment->payment_type === Payment::PAYMENT_TYPE_QUOTA,
            403
        );

        // Idempotensi: payment lunas tidak boleh dibayar lagi.
        if ($payment->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran ini sudah lunas.',
            ], 422);
        }

        if (!$payment->amount || (float) $payment->amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Nominal pembayaran tidak valid.',
            ], 422);
        }

        // Panggilan API Midtrans dibungkus try/catch — endpoint AJAX wajib
        // SELALU mengembalikan JSON, baik sukses maupun gagal.
        try {
            $midtransService = app(\App\Services\MidtransService::class);
            $orderId   = $midtransService->buildOrderId($payment);
            $snapToken = $midtransService->createSnapToken($payment, null, $orderId);

            \App\Models\MidtransAttempt::create([
                'payment_id'   => $payment->id,
                'order_id'     => $orderId,
                'status'       => 'pending',
                'raw_response' => [],
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal membuat Snap token kuota: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat token pembayaran Midtrans: ' . $e->getMessage(),
            ], 502);
        }

        return response()->json([
            'success'        => true,
            'payment_id'     => $payment->id,
            'invoice_number' => $payment->invoice_number,
            'amount'         => (int) $payment->amount,
            'snap_token'     => $snapToken,
            'order_id'       => $orderId,
        ]);
    }

    /**
     * Cek & sinkronkan status pembayaran kuota LANGSUNG ke API Midtrans
     * (server-to-server via order_id attempt — BUKAN percaya browser).
     *
     * Memeriksa SELURUH attempt milik payment (terbaru → terlama) sehingga
     * settlement pada attempt lama tidak terlewat saat user klik "Bayar
     * Sekarang" lagi. Webhook tetap sumber konfirmasi utama; endpoint ini
     * mempercepat UI dan menjadi fallback bila webhook tertunda.
     *
     * Response:
     *   status : status payment aplikasi (pending/paid/rejected/...)
     *   detail : not_found | pending | unknown | settled (informasi UX saja)
     * Tidak ada jalur "mark paid tanpa bukti Midtrans" di sini.
     */
    public function quotaPaymentStatus(Payment $payment)
    {
        abort_unless(
            (int) $payment->company_id === (int) Auth::id()
                && $payment->payment_type === Payment::PAYMENT_TYPE_QUOTA,
            403
        );

        $payment->refresh();

        if ($payment->status === 'paid') {
            return response()->json([
                'success' => true,
                'status'  => 'paid',
                'detail'  => 'settled',
            ]);
        }

        /** @var \Illuminate\Support\Collection<int, \App\Models\MidtransAttempt> $attempts */
        $attempts = \App\Models\MidtransAttempt::where('payment_id', $payment->id)
            ->latest()
            ->get();

        if ($attempts->isEmpty()) {
            return response()->json([
                'success' => true,
                'status'  => $payment->status,
                'detail'  => 'not_created',
            ]);
        }

        $sawPending = false;
        $sawNotFound = false;
        $sawError = false;

        foreach ($attempts as $attempt) {
            try {
                $response = app(\App\Services\MidtransService::class)->getTransactionStatus($attempt->order_id);
            } catch (\Throwable $e) {
                // Transaksi belum ditemukan Midtrans / gangguan → coba attempt lain.
                Log::info('Quota status check inconclusive: ' . $e->getMessage());

                if (str_contains($e->getMessage(), '404')) {
                    $sawNotFound = true;
                } else {
                    $sawError = true;
                }

                continue;
            }

            $arr = json_decode(json_encode($response), true);

            $transactionStatus = $arr['transaction_status'] ?? null;
            $fraudStatus       = $arr['fraud_status'] ?? null;
            $transactionId     = $arr['transaction_id'] ?? null;
            $paymentType       = $arr['payment_type'] ?? null;

            // Mapping identik dengan MidtransWebhookController.
            $targetStatus = match ($transactionStatus) {
                'settlement'                          => 'paid',
                'capture'                             => ($fraudStatus === 'accept' || empty($fraudStatus)) ? 'paid' : 'rejected',
                'deny', 'cancel', 'expire', 'failure' => 'rejected',
                default                               => null, // pending dll — biarkan
            };

            if ($targetStatus === null) {
                if ($transactionStatus === 'pending') {
                    $sawPending = true; // transaksi ADA di Midtrans, belum settlement
                }

                continue;
            }

            if ($targetStatus === $payment->status) {
                return response()->json([
                    'success' => true,
                    'status'  => $payment->status,
                    'detail'  => 'settled',
                ]);
            }

            try {
                DB::transaction(function () use ($payment, $attempt, $targetStatus, $transactionId, $paymentType, $arr) {
                    // Re-read + lock untuk idempotency ketat (race vs webhook).
                    $fresh = Payment::whereKey($payment->id)->lockForUpdate()->first();

                    if ($fresh->status !== 'paid') {
                        $fresh->update([
                            'status'                  => $targetStatus,
                            'midtrans_transaction_id' => $transactionId ?: $fresh->midtrans_transaction_id,
                            'midtrans_payment_type'   => $paymentType ?: $fresh->midtrans_payment_type,
                            'midtrans_response'       => $arr,
                            'verified_at'             => $targetStatus === 'paid' ? now() : $fresh->verified_at,
                        ]);

                        if ($targetStatus === 'paid') {
                            // Income Admin Wallet — idempotent (unique payment_id+type).
                            AdminWalletService::recordProjectQuotaIncome($fresh, Auth::id());
                        }
                    }

                    $attempt->update([
                        'status'       => $targetStatus,
                        'raw_response' => $arr,
                    ]);
                });
            } catch (\Throwable $e) {
                Log::error('Gagal sinkron status kuota: ' . $e->getMessage());

                return response()->json(['success' => false, 'message' => 'Gagal sinkronisasi status.'], 500);
            }

            return response()->json([
                'success' => true,
                'status'  => $targetStatus,
                'detail'  => 'settled',
            ]);
        }

        // Tidak ada attempt yang konklusif — susun detail informatif (UX saja).
        $detail = match (true) {
            $sawPending              => 'pending',    // transaksi ADA tapi belum settlement
            $sawNotFound && !$sawError => 'not_found', // transaksi belum terbentuk di Midtrans
            default                  => 'unknown',    // gangguan API — status belum diketahui
        };

        return response()->json(['success' => true, 'status' => $payment->status, 'detail' => $detail]);
    }

    /* ─── MANUAL / DEMO PAYMENT (KUOTA) ─────────────────────────────
    | Konfirmasi pembayaran kuota TANPA Midtrans/ngrok/webhook — khusus
    | mode demo/testing (config services.midtrans.temporary_confirmation).
    |
    | Keamanan (identik dengan confirmPayment milik workspace):
    |   - Wajib authenticated + pemilik payment (company_id = user login).
    |   - Nominal SELALU dari DATABASE ($payment->amount) — input browser diabaikan.
    |   - Idempotent: payment paid tidak diproses ulang.
    |   - Income Admin Wallet dicatat SEKALI (unique payment_id+type pada wallet_ledger).
    */
    public function confirmQuotaPayment(Payment $payment)
    {
        // 1. Flag demo harus aktif
        if (!(bool) config('services.midtrans.temporary_confirmation', false)) {
            abort(404, 'Manual payment confirmation tidak aktif.');
        }

        // 2. Authorization: hanya company pemilik payment & hanya type quota
        abort_unless(
            (int) $payment->company_id === (int) Auth::id()
                && $payment->payment_type === Payment::PAYMENT_TYPE_QUOTA,
            403
        );

        // 3. Idempotensi: payment lunas tidak diproses ulang
        if ($payment->status === 'paid') {
            return response()->json([
                'success' => true,
                'status'  => 'paid',
                'message' => 'Pembayaran sudah selesai sebelumnya.',
            ]);
        }

        try {
            DB::transaction(function () use ($payment) {
                // Status paid — nominal tetap dari database (bukan request).
                $payment->update([
                    'status'      => 'paid',
                    'verified_by' => Auth::id(),
                    'verified_at' => now(),
                ]);

                // Income Admin Wallet — idempotent (unique payment_id+type).
                AdminWalletService::recordProjectQuotaIncome($payment, Auth::id());
            });
        } catch (\Throwable $e) {
            Log::error('Manual quota confirmation gagal: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengonfirmasi pembayaran.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'status'  => 'paid',
            'message' => 'Pembayaran berhasil dikonfirmasi. Slot proyek Anda telah ditambah.',
        ]);
    }
}
