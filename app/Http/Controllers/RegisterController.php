<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\CompanyAccountRequest;
use App\Models\CompanyProfile;
use App\Models\Policy;
use App\Models\User;
use App\Models\UserConsent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegister(Request $request): View
    {
        // Pertahankan tujuan awal (intended destination) ketika user
        // datang dari flow "Kirim Penawaran", sehingga tetap tersimpan
        // melewati proses register sampai user login.
        $redirect = $request->input('redirect');
        if (is_safe_internal_url($redirect)) {
            $request->session()->put(
                'url.intended',
                $redirect
            );
        }

        // Ambil policy wajib untuk ditampilkan di form
        $termsPolicy = Policy::where('key', Policy::KEY_TERMS)->active()->first();
        $privacyPolicy = Policy::where('key', Policy::KEY_PRIVACY)->active()->first();
        $usagePolicy = Policy::where('key', Policy::KEY_USAGE)->active()->first();

        return view('auth.register', compact('termsPolicy', 'privacyPolicy', 'usagePolicy'));
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $isCompany = $request->boolean('is_company');

        $data = $request->validated();

        $email = Str::lower(trim((string) $data['email']));
        if (User::query()->where('email', $email)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Email sudah digunakan.']);
        }

        // Ambil policy wajib untuk menyimpan consent
        $termsPolicy = Policy::where('key', Policy::KEY_TERMS)->active()->first();
        $privacyPolicy = Policy::where('key', Policy::KEY_PRIVACY)->active()->first();
        $usagePolicy = Policy::where('key', Policy::KEY_USAGE)->active()->first();

        if ($isCompany) {
            // Pastikan company_email belum ada permintaan menunggu
            $active = CompanyAccountRequest::query()
                ->where('company_email', $email)
                ->where('request_status', 'menunggu')
                ->exists();

            if ($active) {
                return back()
                    ->withInput()
                    ->withErrors(['email' => 'Email perusahaan masih memiliki permintaan yang belum diproses.']);
            }

            // Pengaman fallback untuk mencegah nilai NULL di database
            $companyName    = $data['company_name'] ?? $data['name'] ?? 'Nama Perusahaan';
            $companyPhone   = $data['company_phone'] ?? $data['phone'] ?? null;
            $companyAddress = $data['company_address'] ?? null;
            $companyDesc    = $data['company_description'] ?? null;
            $contactPerson  = $data['name'] ?? $companyName;

            // Simpan user company dengan role = company
            $user = User::create([
                'name'     => $companyName,
                'email'    => $email,
                'phone'    => $companyPhone,
                'password' => Hash::make((string) $data['password']),
                'role'     => 'company',
            ]);

            // Simpan consent untuk user
            $this->saveUserConsents($user, $termsPolicy, $privacyPolicy, $usagePolicy, $request);

            // Simpan data otomatis ke tabel CompanyProfile
            CompanyProfile::create([
                'user_id'      => $user->id,
                'company_name' => $companyName,
                'location'     => $companyAddress,
                'phone'        => $companyPhone,
                'description'  => $companyDesc,
            ]);

            // Simpan permintaan akun perusahaan ke database
            CompanyAccountRequest::create([
                'company_name'        => $companyName,
                'contact_person'      => $contactPerson,
                'company_email'       => $email,
                'company_phone'       => $companyPhone,
                'company_address'     => $companyAddress,
                'company_description' => $companyDesc,
                'request_status'      => 'menunggu',
                'reviewed_by'         => null,
                'note'                => null,
            ]);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil. Akun perusahaan Anda sedang menunggu persetujuan Admin.');
        }

        // Freelancer register langsung aktif
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $email,
            'phone'    => $data['phone'],
            'password' => Hash::make((string) $data['password']),
            'role'     => 'freelancer',
        ]);

        // Simpan consent untuk user
        $this->saveUserConsents($user, $termsPolicy, $privacyPolicy, $usagePolicy, $request);

        // Bawa kembali parameter redirect (jika ada dan aman) ke halaman
        // login, sehingga user tetap kembali ke flow kirim penawaran
        // setelah berhasil login. URL sudah divalidasi internal.
        $loginParams = [];

        if (is_safe_internal_url($request->input('redirect'))) {
            $loginParams['redirect'] = $request->input('redirect');
        }

        return redirect()->route('login', $loginParams)
            ->with('success', 'Registrasi berhasil. Silakan login.');
    }

    /**
     * Simpan persetujuan user ke tabel user_consents.
     */
    private function saveUserConsents(User $user, ?Policy $termsPolicy, ?Policy $privacyPolicy, ?Policy $usagePolicy, RegisterRequest $request): void
    {
        $now = now();
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        // Syarat & Ketentuan (wajib)
        if ($termsPolicy) {
            UserConsent::create([
                'user_id'        => $user->id,
                'policy_id'      => $termsPolicy->id,
                'policy_version' => $termsPolicy->version,
                'is_required'    => true,
                'accepted_at'    => $now,
                'ip_address'     => $ip,
                'user_agent'     => $userAgent,
            ]);
        }

        // Kebijakan Privasi (wajib)
        if ($privacyPolicy) {
            UserConsent::create([
                'user_id'        => $user->id,
                'policy_id'      => $privacyPolicy->id,
                'policy_version' => $privacyPolicy->version,
                'is_required'    => true,
                'accepted_at'    => $now,
                'ip_address'     => $ip,
                'user_agent'     => $userAgent,
            ]);
        }

        // Kebijakan Penggunaan Platform (wajib jika ada)
        if ($usagePolicy) {
            UserConsent::create([
                'user_id'        => $user->id,
                'policy_id'      => $usagePolicy->id,
                'policy_version' => $usagePolicy->version,
                'is_required'    => true,
                'accepted_at'    => $now,
                'ip_address'     => $ip,
                'user_agent'     => $userAgent,
            ]);
        }

        // Marketing (opsional)
        if ($request->boolean('marketing_accepted')) {
            // Simpan sebagai consent terpisah dengan is_required = false
            // Bisa menggunakan policy khusus atau flag di tabel yang sama
            // Di sini kita simpan sebagai record terpisah dengan policy_id null atau custom
            // Untuk simplicity, kita gunakan policy usage dengan is_required=false
            if ($usagePolicy) {
                UserConsent::create([
                    'user_id'        => $user->id,
                    'policy_id'      => $usagePolicy->id,
                    'policy_version' => $usagePolicy->version,
                    'is_required'    => false,
                    'accepted_at'    => $now,
                    'ip_address'     => $ip,
                    'user_agent'     => $userAgent,
                ]);
            }
        }
    }
}