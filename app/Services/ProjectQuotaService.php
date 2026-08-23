<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Project;

/**
 * ProjectQuotaService — Aturan "Company Project Upload Quota".
 *
 * FREE  : 3 proyek gratis per bulan kalender.
 * PAID  : setiap proyek tambahan setelah kuota gratis = Rp10.000/per proyek.
 *
 * Penghitungan SELALU berbasis bulan kalender berjalan (bukan total sepanjang
 * waktu), dan dihitung ulang dari database — bukan disimpan sebagai counter,
 * sehingga refresh halaman tidak mengubah status kuota.
 */
class ProjectQuotaService
{
    public const FREE_QUOTA_PER_MONTH = 3;

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

    /** Total slot yang tersedia bulan ini = 3 gratis + slot berbayar. */
    public function availableSlots(int $userId): int
    {
        return self::FREE_QUOTA_PER_MONTH + $this->paidSlotsThisMonth($userId);
    }

    /** Kuota yang sudah terpakai bulan ini. */
    public function usedSlots(int $userId): int
    {
        return $this->projectsCreatedThisMonth($userId);
    }

    /**
     * Apakah company masih boleh membuat proyek bulan ini?
     * projectCount bulan ini < (3 + jumlah payment quota paid bulan ini).
     */
    public function canCreateProject(int $userId): bool
    {
        return $this->projectsCreatedThisMonth($userId) < $this->availableSlots($userId);
    }
}