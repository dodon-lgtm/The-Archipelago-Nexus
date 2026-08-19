<?php

use App\Models\User;
use App\Services\ProfileCompletionService;

if (!function_exists('profile_completion_percentage')) {
    /**
     * Get profile completion percentage for a user.
     *
     * @param User|null $user
     * @return int 0-100
     */
    function profile_completion_percentage(?User $user = null): int
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user) {
            return 0;
        }

        $service = app(ProfileCompletionService::class);
        return $service->calculate($user);
    }
}

if (!function_exists('is_profile_complete')) {
    /**
     * Check if a user's profile is complete enough (>= 80%).
     *
     * @param User|null $user
     * @return bool
     */
    function is_profile_complete(?User $user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user) {
            return false;
        }

        $service = app(ProfileCompletionService::class);
        return $service->isComplete($user);
    }
}

if (!function_exists('is_safe_internal_url')) {
    /**
     * Pastikan URL redirect hanya mengarah ke halaman internal aplikasi.
     *
     * Mengamankan dari open redirect: hanya menerima path absolut internal
     * (contoh: /freelancer/projects/25/penawaran) atau URL absolut yang
     * berawalan base URL aplikasi. Menolak URL eksternal maupun
     * protocol-relative (//evil.com).
     *
     * @param string|null $url
     * @return bool
     */
    function is_safe_internal_url(?string $url): bool
    {
        if (!$url || trim($url) === '') {
            return false;
        }

        // Path absolut internal, tetapi bukan protocol-relative.
        if (str_starts_with($url, '/')) {
            return !str_starts_with($url, '//');
        }

        // URL absolut yang berawalan base aplikasi (dengan trailing slash).
        $base = url('/');

        return str_starts_with($url, $base);
    }
}

if (!function_exists('get_missing_profile_fields')) {
    /**
     * Get list of missing mandatory fields for a user.
     *
     * @param User|null $user
     * @return array
     */
    function get_missing_profile_fields(?User $user = null): array
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user) {
            return [];
        }

        $service = app(ProfileCompletionService::class);
return $service->getMissingMandatoryFields($user);
    }
}