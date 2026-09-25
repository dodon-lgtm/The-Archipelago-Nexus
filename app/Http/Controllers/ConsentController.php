<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\UserConsent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConsentController extends Controller
{
    /**
     * Tampilkan halaman persetujuan ulang untuk policy yang versi-nya berubah.
     */
    public function showRequiredConsent(): View
    {
        $user = Auth::user();
        $pendingPolicies = $user->getPendingRequiredPolicies();

        return view('consent.required', compact('pendingPolicies'));
    }

    /**
     * Simpan persetujuan ulang dari user.
     */
    public function storeRequiredConsent(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $pendingPolicies = $user->getPendingRequiredPolicies();

        // Validasi: semua policy pending harus dicentang
        $rules = [];
        $messages = [];
        foreach ($pendingPolicies as $policy) {
            $rules["policy_{$policy->id}"] = ['required', 'accepted'];
            $messages["policy_{$policy->id}.required"] = "Anda harus menyetujui {$policy->title} versi {$policy->version} untuk melanjutkan.";
            $messages["policy_{$policy->id}.accepted"] = "Anda harus menyetujui {$policy->title} versi {$policy->version} untuk melanjutkan.";
        }

        $request->validate($rules, $messages);

        $now = now();
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        foreach ($pendingPolicies as $policy) {
            UserConsent::create([
                'user_id'        => $user->id,
                'policy_id'      => $policy->id,
                'policy_version' => $policy->version,
                'is_required'    => true,
                'accepted_at'    => $now,
                'ip_address'     => $ip,
                'user_agent'     => $userAgent,
            ]);
        }

        return redirect()->intended(route('freelancer.dashboard'))
            ->with('success', 'Persetujuan kebijakan berhasil diperbarui. Selamat datang kembali!');
    }
}
