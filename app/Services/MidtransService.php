<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Models\Payment;
use App\Models\Workspace;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MidtransService
{
    /**
     * Pemisah antara invoice_number dan suffix unik pada order_id Midtrans.
     * Invoice number di aplikasi SELALU berformat "INV-YYYYMMDD-XXXX" (tanpa underscore),
     * sehingga underscore aman dipakai sebagai pemisah dan order_id selalu bisa
     * di-resolve kembali ke invoice number.
     */
    public const ORDER_ID_SEPARATOR = '_';

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
     *
     * Order ID dibuat UNIK per percobaan (invoice_number + suffix acak).
     * Hal ini WAJIB karena Midtrans tidak mengizinkan order_id yang sama dipakai
     * ulang selama transaksi lama (mis. popup Snap ditutup tanpa membayar) masih
     * tercatat sebagai order_id aktif/expire. Webhook memetakan kembali order_id
     * ke Payment via resolveInvoiceFromOrderId().
     */
    public function createSnapToken(Payment $payment, ?Workspace $workspace = null, ?string $orderId = null): string
    {
        $this->configure();

        $orderId = $orderId ?? $this->buildOrderId($payment);

        // Untuk payment QUOTA (tanpa workspace), detail customer diambil dari
        // relasi payment->company (User), dan nama item disesuaikan.
        $customerName = $workspace?->company?->name ?? $payment->company?->name ?? 'Company';
        $customerEmail = $workspace?->company?->email ?? $payment->company?->email ?? 'company@example.com';

        $itemId = $workspace?->id ?? $payment->id;
        $itemName = $payment->isQuotaPayment()
            ? 'Kuota Proyek Tambahan (Rp ' . number_format((int) round($payment->amount), 0, ',', '.') . ')'
            : ('Pembayaran Proyek: ' . ($workspace?->project?->project_name ?? 'Workspace #' . $workspace?->id));

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) round($payment->amount),
            ],
            'customer_details' => [
                'first_name' => $customerName,
                'email' => $customerEmail,
            ],
            'item_details' => [
                [
                    'id' => $itemId,
                    'price' => (int) round($payment->amount),
                    'quantity' => 1,
                    'name' => $itemName,
                ],
            ],
            // Pastikan Midtrans mengirimkan webhook ke endpoint yang dapat dijangkau publik.
            // Nilai dihasilkan dari APP_URL. Pada development lokal, gunakan tunnel (ngrok)
            // sehingga APP_URL=https://<PUBLIC-TUNNEL-DOMAIN>. Webhook akan dikirim ke:
            //   https://<PUBLIC-TUNNEL-DOMAIN>/api/midtrans/notification
            // Ini mencegah Midtrans menggunakan localhost URL dari Dashboard yang tidak
            // dapat dijangkau oleh server Midtrans.
            'notification_url' => url('/api/midtrans/notification'),
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
     * Build a unique Midtrans order_id for a single Snap attempt.
     * Contoh hasil: "INV-20260816-0002_a1b2c3"
     */
    public function buildOrderId(Payment $payment): string
    {
        return $payment->invoice_number . self::ORDER_ID_SEPARATOR . Str::lower(Str::random(6));
    }

    /**
     * Resolve original invoice_number from a (possibly suffixed) order_id.
     * Aman selama invoice_number tidak mengandung separator (underscore).
     */
    public static function resolveInvoiceFromOrderId(string $orderId): string
    {
        $separatorPos = strpos($orderId, self::ORDER_ID_SEPARATOR);

        return $separatorPos === false ? $orderId : substr($orderId, 0, $separatorPos);
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
