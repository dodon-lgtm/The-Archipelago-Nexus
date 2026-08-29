<?php

namespace App\Services;

use App\Models\User;
use App\Models\Withdrawal;
use App\Services\AdminWalletService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * WithdrawalService - Memusatkan logika pengajuan & pemrosesan penarikan dana.
 *
 * Saldo freelancer bersifat computed (total pembayaran "paid" dikurangi
 * seluruh penarikan aktif & yang sudah berhasil), sehingga penolakan
 * otomatis "mengembalikan" saldo tanpa memerlukan kolom saldo terpisah.
 *
 * Seluruh transaksi yang menyentuh saldo dibungkus DB::transaction dan
 * mengunci baris user (lockForUpdate) untuk mencegah double-submit.
 */
class WithdrawalService
{
    // ────────────────────────────────────────────────────────────
    // PROVIDER FEE (terpusat di config/withdrawal.php)
    // ----------------------------------------------------------------
    // Fee provider adalah BIAYA EKSTERNAL provider pembayaran — berbeda
    // dari platform fee 5% freelancer. Hanya dipakai penarikan ADMIN.
    // Semua angka WAJIB dibaca lewat helper ini, dilarang hardcode.
    // ────────────────────────────────────────────────────────────

    /** Daftar metode provider yang valid dari config. */
    public static function providerMethods(): array
    {
        return array_keys(config('withdrawal.providers', []));
    }

    public static function providerLabel(string $method): string
    {
        return config("withdrawal.providers.{$method}.label", ucfirst($method));
    }

    /**
     * Hitung fee provider untuk sebuah metode & nominal.
     * Hasil dibulatkan ke rupiah penuh.
     */
    public static function calculateProviderFee(string $method, float $amount): float
    {
        $fee = config("withdrawal.providers.{$method}.fee");

        if (!is_array($fee) || $amount <= 0) {
            return 0.0;
        }

        if (($fee['type'] ?? '') === 'fixed') {
            return round((float) ($fee['amount'] ?? 0), 2);
        }

        if (($fee['type'] ?? '') === 'percent') {
            return round($amount * ((float) ($fee['amount'] ?? 0)) / 100, 2);
        }

        return 0.0;
    }

    /**
     * Buat pengajuan penarikan baru.
     *
     * Karena ini SIMULASI, penarikan langsung dianggap berhasil/dicairkan
     * (status "berhasil" + paid_at diisi saat itu juga). Akibatnya saldo
     * tersedia freelancer langsung berkurang sesuai nominal penarikan.
     *
     * Fee withdrawal platform dihitung DI SINI (backend) dari Financial Settings
     * dan disimpan (snapshot) ke kolom `fee`, `fee_rate`, serta `net_amount`
     * agar konsisten di seluruh tampilan.
     *
     * @param  array  $data  Data yang sudah lolos WithdrawalStoreRequest
     * @return Withdrawal
     */
    public function store(array $data, int $userId): Withdrawal
    {
        return DB::transaction(function () use ($data, $userId) {
            // Kunci baris user agar dua request bersamaan tidak bisa
            // menghabiskan saldo yang sama (anti double-submit).
            $user = User::whereKey($userId)->lockForUpdate()->firstOrFail();

            $available = $this->availableBalance($userId);

            if ((float) $data['amount'] > $available) {
                throw ValidationException::withMessages([
                    'amount' => 'Nominal penarikan tidak boleh melebihi saldo tersedia (Rp '
                        . number_format($available, 0, ',', '.') . ').',
                ]);
            }

            // Fee withdrawal dihitung dari rate Financial Settings (sekali saja saat dibuat),
            // dan di-snapshot ke kolom fee_rate agar transaksi lama tidak berubah.
            $amount    = (float) $data['amount'];
            $feeRate   = (float) \App\Models\FinancialSetting::getSettings()->withdrawalFeeRate();
            $fee       = round($amount * $feeRate / 100, 2);
            $netAmount = round($amount - $fee, 2);

            $withdrawal = Withdrawal::create([
                'withdrawal_code' => $this->generateCode(),
                'user_id'         => $userId,
                'amount'          => $amount,
                'fee'             => $fee,
                'fee_rate'        => $feeRate,
                'net_amount'      => $netAmount,
                'method'          => $data['method'],
                'bank_name'       => $data['bank_name'],
                'account_name'    => $data['account_name'],
                'account_number'  => $data['account_number'],
                'status'          => Withdrawal::STATUS_BERHASIL,
                'paid_at'         => now(),
            ]);

            // Fee 5% masuk ke Admin Wallet SEBAGAI INCOME — idempotent
            // (unique index withdrawal_id+type; jika store dipanggil berulang,
            // AdminWalletService hanya mencatat sekali).
            AdminWalletService::recordWithdrawalFee($withdrawal, $userId);

            NotificationService::sendTo(
                user: $userId,
                type: 'withdrawal.created',
                title: 'Penarikan Dana Berhasil',
                message: 'Penarikan ' . $withdrawal->withdrawal_code
                    . ' sebesar Rp ' . number_format($amount, 0, ',', '.')
                    . ' berhasil dicairkan (simulasi). Fee admin sebesar Rp '
                    . number_format($fee, 0, ',', '.')
                    . ' dipotong, dan Rp ' . number_format($netAmount, 0, ',', '.')
                    . ' diterima ke ' . $withdrawal->bank_name
                    . ' (' . $withdrawal->account_number . ').',
                redirect: route('freelancer.pendapatan.index'),
                senderId: $userId,
                metadata: ['withdrawal_id' => $withdrawal->id],
            );

            return $withdrawal;
        });
    }

    /**
     * Admin memproses penarikan (menunggu -> diproses).
     */
    public function process(Withdrawal $withdrawal, int $adminId): Withdrawal
    {
        DB::transaction(function () use ($withdrawal, $adminId) {
            if ($withdrawal->status !== Withdrawal::STATUS_MENUNGGU) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya penarikan berstatus "Menunggu" yang dapat diproses.',
                ]);
            }

            $withdrawal->update([
                'status'       => Withdrawal::STATUS_DIPROSES,
                'processed_by' => $adminId,
                'processed_at' => now(),
            ]);

            $this->notifyFreelancer($withdrawal, 'withdrawal.processing', 'Penarikan Sedang Diproses',
                'Penarikan ' . $withdrawal->withdrawal_code
                . ' sebesar Rp ' . number_format((float) $withdrawal->amount, 0, ',', '.')
                . ' sedang diproses oleh admin.', $adminId);
        });

        return $withdrawal->fresh();
    }

    /**
     * Admin menyetujui penarikan (menunggu/diproses -> berhasil).
     * Simulasi payout: tidak ada uang sungguhan yang dikirim.
     */
    public function approve(Withdrawal $withdrawal, int $adminId): Withdrawal
    {
        DB::transaction(function () use ($withdrawal, $adminId) {
            if (!in_array($withdrawal->status, Withdrawal::ACTIVE_STATUSES, true)) {
                throw ValidationException::withMessages([
                    'status' => 'Penarikan ini tidak dapat disetujui pada status saat ini.',
                ]);
            }

            $withdrawal->update([
                'status'       => Withdrawal::STATUS_BERHASIL,
                'processed_by' => $adminId,
                'processed_at' => now(),
                'paid_at'      => now(),
            ]);

            // Fee 5% (jika belum tercatat saat store) masuk ke Admin Wallet.
            // Idempotent — tidak akan tercatat dua kali walau approve dipanggil ulang.
            AdminWalletService::recordWithdrawalFee($withdrawal, $adminId);

            $this->notifyFreelancer($withdrawal, 'withdrawal.success', 'Penarikan Berhasil',
                'Penarikan ' . $withdrawal->withdrawal_code
                . ' sebesar Rp ' . number_format((float) $withdrawal->amount, 0, ',', '.')
                . ' telah berhasil dicairkan ke ' . $withdrawal->method_label . ' ' . $withdrawal->bank_name
                . ' (' . $withdrawal->account_number . ').', $adminId);
        });

        return $withdrawal->fresh();
    }

    /**
     * Admin menolak penarikan (menunggu/diproses -> ditolak).
     * Saldo otomatis kembali tersedia karena dihitung ulang dari payments.
     */
    public function reject(Withdrawal $withdrawal, int $adminId, ?string $reason): Withdrawal
    {
        DB::transaction(function () use ($withdrawal, $adminId, $reason) {
            if (!in_array($withdrawal->status, Withdrawal::ACTIVE_STATUSES, true)) {
                throw ValidationException::withMessages([
                    'status' => 'Penarikan ini tidak dapat ditolak pada status saat ini.',
                ]);
            }

            $withdrawal->update([
                'status'            => Withdrawal::STATUS_DITOLAK,
                'rejection_reason'  => $reason,
                'processed_by'      => $adminId,
                'processed_at'      => now(),
            ]);

            $message = 'Penarikan ' . $withdrawal->withdrawal_code
                . ' sebesar Rp ' . number_format((float) $withdrawal->amount, 0, ',', '.')
                . ' ditolak oleh admin. Saldo telah dikembalikan ke saldo tersedia Anda.';
            if ($reason) {
                $message .= ' Alasan: ' . $reason;
            }

            $this->notifyFreelancer($withdrawal, 'withdrawal.rejected', 'Penarikan Ditolak', $message, $adminId);
        });

        return $withdrawal->fresh();
    }

    /**
     * Saldo tersedia untuk user tertentu (dipakai ulang untuk konsistensi
     * antara FormRequest, service, dan tampilan).
     */
    public function availableBalance(int $userId): float
    {
        // Hanya dana escrow yang sudah resolved (released / released_partial)
        // boleh dicairkan. Payment paid dengan funds_status held/disputed
        // masih tertahan di escrow dan TIDAK boleh masuk saldo tersedia.
        $totalEarned = (float) \App\Models\Payment::where('freelancer_id', $userId)
            ->where('status', 'paid')
            ->whereIn('funds_status', [
                \App\Models\Payment::FUNDS_RELEASED,
                \App\Models\Payment::FUNDS_RELEASED_PARTIAL,
            ])
            ->sum('freelancer_receive');

        $reserved = (float) Withdrawal::forUser($userId)->active()->sum('amount');

        $withdrawn = (float) Withdrawal::forUser($userId)
            ->where('status', Withdrawal::STATUS_BERHASIL)
            ->sum('amount');

        return max(0.0, $totalEarned - $reserved - $withdrawn);
    }

    protected function generateCode(): string
    {
        do {
            $code = 'WDR-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Withdrawal::where('withdrawal_code', $code)->exists());

        return $code;
    }

    protected function notifyFreelancer(
        Withdrawal $withdrawal,
        string $type,
        string $title,
        string $message,
        ?int $senderId = null
    ): void {
        NotificationService::sendTo(
            user: $withdrawal->user_id,
            type: $type,
            title: $title,
            message: $message,
            redirect: route('freelancer.pendapatan.index'),
            senderId: $senderId ?: Auth::id(),
            metadata: ['withdrawal_id' => $withdrawal->id],
        );
    }
}