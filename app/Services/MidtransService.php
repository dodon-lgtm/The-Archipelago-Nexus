<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Models\Payment;
use App\Models\Workspace;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        $this->configure();
    }

    /**
     * Configure Midtrans SDK credentials and environment.
     */
    public function configure(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create Snap Token for payment transaction.
     */
    public function createSnapToken(Payment $payment, Workspace $workspace): string
    {
        $this->configure();

        $params = [
            'transaction_details' => [
                'order_id' => $payment->invoice_number,
                'gross_amount' => (int) round($payment->amount),
            ],
            'customer_details' => [
                'first_name' => $workspace->company->name ?? 'Company',
                'email' => $workspace->company->email ?? 'company@example.com',
            ],
            'item_details' => [
                [
                    'id' => $workspace->id,
                    'price' => (int) round($payment->amount),
                    'quantity' => 1,
                    'name' => 'Pembayaran Proyek: ' . ($workspace->project->project_name ?? 'Workspace #' . $workspace->id),
                ],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans getSnapToken Error: ' . $e->getMessage());
            throw new \Exception('Gagal membuat token pembayaran Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * Verify Midtrans notification signature and data.
     */
    public function verifyNotification(array $notificationData = []): array
    {
        $this->configure();

        $notification = new \Midtrans\Notification();

        return [
            'order_id' => $notification->order_id,
            'status_code' => $notification->status_code,
            'gross_amount' => $notification->gross_amount,
            'signature_key' => $notification->signature_key,
            'transaction_status' => $notification->transaction_status,
            'payment_type' => $notification->payment_type ?? null,
            'fraud_status' => $notification->fraud_status ?? null,
            'raw_response' => (array) $notification,
        ];
    }

    /**
     * Get transaction status directly from Midtrans API.
     */
    public function getTransactionStatus(string $orderId): mixed
    {
        $this->configure();

        try {
            return Transaction::status($orderId);
        } catch (\Exception $e) {
            Log::error('Midtrans getTransactionStatus Error for order ' . $orderId . ': ' . $e->getMessage());
            throw new \Exception('Gagal mengambil status transaksi Midtrans: ' . $e->getMessage());
        }
    }
}
