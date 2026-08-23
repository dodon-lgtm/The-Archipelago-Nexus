<?php

namespace App\Http\Controllers;

use App\Models\MidtransAttempt;
use App\Models\Payment;
use App\Services\AdminWalletService;
use App\Services\EscrowService;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    /**
     * Handle incoming Midtrans payment notification/webhook.
     */
    public function handleNotification(Request $request): JsonResponse
    {
        $payload = $request->all();

        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');
        $paymentType = $request->input('payment_type');
        $transactionId = $request->input('transaction_id');

        Log::info('Midtrans Webhook: Notification received', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'transaction_status' => $transactionStatus,
            'payment_type' => $paymentType,
        ]);

        // 1. Validasi keberadaan parameter utama
        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey || !$transactionStatus) {
            Log::warning('Midtrans Webhook: Invalid notification payload structure');
            return response()->json([
                'status' => 'error',
                'message' => 'Payload notifikasi Midtrans tidak lengkap.'
            ], 400);
        }

        // 2. Signature Verification
        $serverKey = config('services.midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Midtrans Webhook: Invalid signature', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Signature key tidak valid.'
            ], 403);
        }

        // 3. Payment Lookup
        // order_id dari Midtrans bisa berupa nilai eksak invoice_number (kompatibilitas lama)
        // atau nilai unik per-attempt "{invoice_number}_{suffix}" produksi MidtransService::buildOrderId().
        $payment = Payment::where('invoice_number', $orderId)->first();

        if (!$payment) {
            $resolvedInvoice = MidtransService::resolveInvoiceFromOrderId($orderId);

            if ($resolvedInvoice !== $orderId) {
                $payment = Payment::where('invoice_number', $resolvedInvoice)->first();
            }
        }

        if (!$payment) {
            Log::warning('Midtrans Webhook: Payment not found for order ID', [
                'order_id' => $orderId
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Data pembayaran tidak ditemukan.'
            ], 404);
        }

        // 3b. Validasi attempt (jika order_id adalah attempt yang tercatat).
        //     Mencegah order_id milik Payment lain dipakai untuk memvalidasi Payment ini.
        $attempt = MidtransAttempt::where('order_id', $orderId)->first();

        if ($attempt && (int) $attempt->payment_id !== (int) $payment->id) {
            Log::warning('Midtrans Webhook: order_id belongs to a different payment', [
                'order_id' => $orderId,
                'attempt_payment_id' => $attempt->payment_id,
                'resolved_payment_id' => $payment->id,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Order ID tidak sesuai dengan pembayaran.'
            ], 409);
        }

        // 4. Amount Integrity Check
        $notificationAmount = (float) $grossAmount;
        $dbAmount = (float) $payment->amount;

        if (abs($notificationAmount - $dbAmount) > 0.01) {
            Log::error('Midtrans Webhook: Amount mismatch', [
                'order_id' => $orderId,
                'notification_amount' => $notificationAmount,
                'database_amount' => $dbAmount,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Nominal transaksi tidak cocok dengan database.'
            ], 400);
        }

        // 5. Idempotency Check (Jika sudah paid, hindari re-proses ganda)
        if ($payment->status === 'paid') {
            Log::info('Midtrans Webhook: Payment already marked as paid, skipping duplicate processing', [
                'order_id' => $orderId
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran sudah lunas / terproses sebelumnya.'
            ], 200);
        }

        // 6. Map Transaction Status ke Status Payment Aplikasi
        $targetStatus = match ($transactionStatus) {
            'settlement' => 'paid',
            'capture' => ($fraudStatus === 'accept' || empty($fraudStatus)) ? 'paid' : 'rejected',
            'pending' => 'pending',
            'deny', 'cancel', 'expire', 'failure' => 'rejected',
            default => 'pending',
        };

        // 7. Update Database dalam Transaction Block
        try {
            DB::transaction(function () use ($payment, $targetStatus, $transactionId, $paymentType, $payload, $attempt) {
                $payment->update([
                    'status' => $targetStatus,
                    'midtrans_transaction_id' => $transactionId ?: $payment->midtrans_transaction_id,
                    'midtrans_payment_type' => $paymentType ?: $payment->midtrans_payment_type,
                    'midtrans_response' => $payload,
                    'verified_at' => $targetStatus === 'paid' ? now() : $payment->verified_at,
                ]);

                // Catat status pada attempt (jika order_id adalah attempt yang tercatat)
                if ($attempt) {
                    $attempt->update([
                        'status' => $targetStatus,
                        'raw_response' => $payload,
                    ]);
                }

                if ($targetStatus === 'paid') {
                    // QUOTA: payment kuota proyek → catat INCOME Admin Wallet (idempotent).
                    // Tidak ada escrow / workspace edit karena belum ada proyek.
                    if ($payment->isQuotaPayment()) {
                        app(AdminWalletService::class)->recordProjectQuotaIncome($payment);
                    } elseif ($payment->workspace) {
                        // Dana otomatis DITAHAN (escrow) — belum menjadi pendapatan freelancer.
                        // Idempotent: jika sudah held/disputed/resolved, hold() menjadi no-op.
                        app(EscrowService::class)->hold($payment);

                        $payment->workspace->update(['status' => 'Sedang Dikerjakan']);
                    }
                }
            });

            Log::info('Midtrans Webhook: Payment status updated successfully', [
                'order_id' => $orderId,
                'new_status' => $targetStatus,
                'transaction_id' => $transactionId,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi Midtrans berhasil diproses.'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Midtrans Webhook: Failed to update payment status', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan internal saat memperbarui status.'
            ], 500);
        }
    }
}
