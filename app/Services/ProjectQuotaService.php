<?php

namespace App\Services;

use App\Models\FinancialSetting;
use App\Models\Payment;
use App\Models\Project;

/**
 * ProjectQuotaService — Aturan "Company Project Upload Quota".
 *
 * FREE  : N proyek gratis per bulan kalender (diatur Admin via Financial Settings).
 * PAID  : setiap proyek tambahan setelah kuota gratis dibayar (harga dari Financial Settings).
 *
 * Penghitungan SELALU berbasis bulan kalender berjalan (bukan total sepanjang
 * waktu), dan dihitung ulang dari database — bukan disimpan sebagai counter,
 * sehingga refresh halaman tidak mengubah status kuota.
 */
class ProjectQuotaService
{
    /** Fallback bila tabel financial_settings belum ada/berisi. */
    public const FREE_QUOTA_PER_MONTH = 3;

    /** Batas gratis per bulan dari pengaturan aktif (fallback ke konstanta). */
    public function freeQuota(): int
    {
        return FinancialSetting::getSettings()->freeUploadsPerMonth();
    }

    /** Jumlah proyek yang dibuat company pada bulan berjalan. */
    public function projectsCreatedThisMonth(int $userId): int
    {
        return Project::where('user_id', $userId)
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<', now()->startOfMonth()->addMonth())
            ->count();
    }

    /** Jumlah kuota berbayar (payment quota paid) yang sudah dipakai bulan ini. */
    public function paidSlotsThisMonth(int $userId): int
    {
        return Payment::where('company_id', $userId)
            ->where('payment_type', Payment::PAYMENT_TYPE_QUOTA)
            ->where('status', 'paid')
            ->where('verified_at', '>=', now()->startOfMonth())
            ->where('verified_at', '<', now()->startOfMonth()->addMonth())
            ->count();
    }

    /** Total slot yang tersedia bulan ini = N gratis + slot berbayar. */
    public function availableSlots(int $userId): int
    {
        return $this->freeQuota() + $this->paidSlotsThisMonth($userId);
    }

    /** Kuota yang sudah terpakai bulan ini. */
    public function usedSlots(int $userId): int
    {
        return $this->projectsCreatedThisMonth($userId);
    }

    /**
     * Apakah company masih boleh membuat proyek bulan ini?
     * projectCount bulan ini < (N + jumlah payment quota paid bulan ini).
     */
    public function canCreateProject(int $userId): bool
    {
        return $this->projectsCreatedThisMonth($userId) < $this->availableSlots($userId);
    }
}