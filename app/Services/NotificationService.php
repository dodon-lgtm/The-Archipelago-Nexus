<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

/**
 * NotificationService - Memusatkan logika pembuatan notifikasi.
 *
 * Seluruh controller WAJIB menggunakan service ini untuk membuat notifikasi,
 * bukan memanggil Notification::create() secara langsung.
 *
 * Laravel 12 compatible.
 */
class NotificationService
{
    /**
     * Kirim notifikasi ke seorang user.
     *
     * @param  array  $data  Data notifikasi dengan key:
     *                        - user_id (int, required)          : ID penerima notifikasi
     *                        - type (string, required)          : Tipe notifikasi (contoh: offer.sent)
     *                        - title (string, required)         : Judul notifikasi
     *                        - message (string, required)       : Isi pesan notifikasi
     *                        - sender_id (int|null, optional)   : ID pengirim notifikasi
     *                        - redirect (string|null, optional) : URL redirect (disimpan di data->redirect)
     *                        - penawaran_id (int|null, optional)
     *                        - workspace_id (int|null, optional)
     *                        - project_id (int|null, optional)
     *                        - payment_id (int|null, optional)
     *                        - company_account_request_id (int|null, optional)
     * @return Notification|null
     */
    public static function send(array $data): ?Notification
    {
        try {
            // Ekstrak redirect URL dari data khusus
            $redirect = $data['redirect'] ?? null;
            unset($data['redirect']);

            // Siapkan JSON data untuk menyimpan redirect URL dan metadata lainnya
            $jsonData = [];
            if ($redirect) {
                $jsonData['redirect'] = $redirect;
            }

            // Tambahkan metadata tambahan jika ada
            if (isset($data['metadata']) && is_array($data['metadata'])) {
                $jsonData = array_merge($jsonData, $data['metadata']);
                unset($data['metadata']);
            }

            $data['data'] = !empty($jsonData) ? $jsonData : null;

            return Notification::create($data);
        } catch (\Exception $e) {
            Log::error('Gagal membuat notifikasi: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Kirim notifikasi dengan parameter yang lebih eksplisit dan readable.
     *
     * @param  int          $user       ID penerima notifikasi
     * @param  string       $type       Tipe notifikasi (contoh: offer.sent)
     * @param  string       $title      Judul notifikasi
     * @param  string       $message    Isi pesan notifikasi
     * @param  string|null  $redirect   URL redirect (disimpan di data->redirect)
     * @param  int|null     $senderId   ID pengirim notifikasi
     * @param  int|null     $penawaranId
     * @param  int|null     $workspaceId
     * @param  int|null     $projectId
     * @param  int|null     $paymentId
     * @param  int|null     $companyAccountRequestId
     * @param  array|null   $metadata   Data tambahan (akan digabung ke kolom data JSON)
     * @return Notification|null
     */
    public static function sendTo(
        int $user,
        string $type,
        string $title,
        string $message,
        ?string $redirect = null,
        ?int $senderId = null,
        ?int $penawaranId = null,
        ?int $workspaceId = null,
        ?int $projectId = null,
        ?int $paymentId = null,
        ?int $companyAccountRequestId = null,
        ?array $metadata = null,
    ): ?Notification {
        return self::send([
            'user_id'                  => $user,
            'sender_id'                => $senderId,
            'type'                     => $type,
            'title'                    => $title,
            'message'                  => $message,
            'redirect'                 => $redirect,
            'penawaran_id'             => $penawaranId,
            'workspace_id'             => $workspaceId,
            'project_id'               => $projectId,
            'payment_id'               => $paymentId,
            'company_account_request_id' => $companyAccountRequestId,
            'metadata'                 => $metadata,
        ]);
    }
}
