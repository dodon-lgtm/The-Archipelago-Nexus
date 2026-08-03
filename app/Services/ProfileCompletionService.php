<?php

namespace App\Services;

use App\Models\User;
use App\Models\FreelancerProfile;
use App\Models\CompanyProfile;

class ProfileCompletionService
{
    /**
     * Mandatory fields for profile completion check.
     * Each mandatory field = 20% weight (5 fields x 20% = 80% to pass).
     */
    private const MANDATORY_FIELDS = [
        'name',
        'email',
        'phone',
        'location',
        'skills',
    ];

    /**
     * Optional fields for additional profile completion.
     * Each optional field adds extra percentage beyond 80%.
     */
    private const OPTIONAL_FIELDS = [
        'photo',
        'bio',
        'portfolio',
    ];

    /**
     * Calculate profile completion percentage for a user.
     *
     * @param User $user
     * @return int 0-100
     */
    public function calculate(User $user): int
    {
        if ($user->role === 'freelancer') {
            return $this->calculateFreelancerProfile($user);
        }

        if ($user->role === 'company') {
            return $this->calculateCompanyProfile($user);
        }

        // Admin doesn't need profile completion
        return 100;
    }

    /**
     * Check if user's profile meets the minimum completion threshold (80%).
     *
     * @param User $user
     * @return bool
     */
    public function isComplete(User $user): bool
    {
        return $this->calculate($user) >= 80;
    }

    /**
     * Get the list of missing mandatory fields for a user.
     *
     * @param User $user
     * @return array
     */
    public function getMissingMandatoryFields(User $user): array
    {
        $missing = [];

        // Check user-level mandatory fields
        if (empty($user->name)) $missing[] = 'Nama';
        if (empty($user->email)) $missing[] = 'Email';
        if (empty($user->phone)) $missing[] = 'Nomor Telepon';

        if ($user->role === 'freelancer') {
            $profile = $user->freelanceProfile;
            if (!$profile || empty($profile->location)) $missing[] = 'Lokasi';
            if (!$profile || empty($profile->skills)) $missing[] = 'Skill/Keahlian';
        } elseif ($user->role === 'company') {
            $profile = $user->companyProfile;
            if (!$profile || empty($profile->location)) $missing[] = 'Lokasi';
            if (!$profile || empty($profile->company_name)) $missing[] = 'Nama Perusahaan';
            // For company, we use company_name as a mandatory field equivalent to skills
        }

        return $missing;
    }

    /**
     * Calculate profile completion for freelancer.
     *
     * @param User $user
     * @return int
     */
    private function calculateFreelancerProfile(User $user): int
    {
        $profile = $user->freelanceProfile;
        $percentage = 0;

        // Mandatory fields: each 16% (5 fields = 80%)
        if (!empty($user->name)) $percentage += 16;
        if (!empty($user->email)) $percentage += 16;
        if (!empty($user->phone)) $percentage += 16;

        if ($profile) {
            if (!empty($profile->location)) $percentage += 16;
            if (!empty($profile->skills)) $percentage += 16;

            // Optional fields: each adds extra (max 20%)
            $optionalBonus = 0;
            if (!empty($profile->photo)) $optionalBonus += 7;
            if (!empty($profile->bio)) $optionalBonus += 7;
            if (!empty($profile->portfolio_link)) $optionalBonus += 6;

            $percentage += min($optionalBonus, 20);
        }

        return min($percentage, 100);
    }

    /**
     * Calculate profile completion for company.
     *
     * @param User $user
     * @return int
     */
    private function calculateCompanyProfile(User $user): int
    {
        $profile = $user->companyProfile;
        $percentage = 0;

        // Mandatory fields: each 16% (5 fields = 80%)
        if (!empty($user->name)) $percentage += 16;
        if (!empty($user->email)) $percentage += 16;
        if (!empty($user->phone)) $percentage += 16;

        if ($profile) {
            if (!empty($profile->location)) $percentage += 16;
            if (!empty($profile->company_name)) $percentage += 16;

            // Optional fields: each adds extra (max 20%)
            $optionalBonus = 0;
            if (!empty($profile->company_logo)) $optionalBonus += 7;
            if (!empty($profile->description)) $optionalBonus += 7;
            if (!empty($profile->website)) $optionalBonus += 6;

            $percentage += min($optionalBonus, 20);
        }

        return min($percentage, 100);
    }
}
