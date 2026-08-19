<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http; // 👈 Menambahkan HTTP Client bawaan Laravel
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = Str::lower(trim((string) $request->input('email')));
        $password = (string) $request->input('password');

        $user = User::query()->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withInput()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        }

        // Aturan login company: harus disetujui admin
        if ($user->role === 'company') {
            $companyRequest =
                \App\Models\CompanyAccountRequest::query()
                ->where('company_email', $user->email)
                ->where('request_status', 'disetujui')
                ->first();

            if (!$companyRequest) {
                return back()->withInput()->withErrors([
                    'email' => 'Akun perusahaan Anda belum disetujui admin. Silakan menunggu persetujuan.',
                ]);
            }
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended($this->redirectPathByRole($user));
    }

    /**
     * Memproses callback dari Google Login tanpa memerlukan library external (Google_Client)
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        $idToken = $request->input('id_token');

        if (!$idToken) {
            return redirect()->route('login')->withErrors(['email' => 'Token Google tidak ditemukan.']);
        }

        // 1. Verifikasi token langsung ke endpoint API Google
        $response = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $idToken,
        ]);

        // 2. Cek apakah respon Google sukses
        if ($response->successful()) {
            $payload = $response->json();

            // Memastikan token sesuai dengan Client ID yang terdaftar di .env
            if (isset($payload['aud']) && $payload['aud'] === config('services.google.client_id')) {
                $email = Str::lower(trim($payload['email']));
                $name = $payload['name'];

                // 3. Cari user berdasarkan email, jika belum ada buatkan baru
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'password' => bcrypt(Str::random(16)), // Password acak aman
                        // 'role' => 'freelancer', // Opsional: Berikan default role jika diperlukan di database Anda
                    ]
                );

                // Cek khusus jika user bereperan sebagai company
                if ($user->role === 'company') {
                    $companyRequest = \App\Models\CompanyAccountRequest::query()
                        ->where('company_email', $user->email)
                        ->where('request_status', 'disetujui')
                        ->first();

                    if (!$companyRequest) {
                        return redirect()->route('login')->withErrors([
                            'email' => 'Akun perusahaan Anda belum disetujui admin.',
                        ]);
                    }
                }

                // 4. Auto-login dan regenerate session
                Auth::login($user);
                $request->session()->regenerate();

                // Redirect sesuai role user
                return redirect()->intended($this->redirectPathByRole($user))->with('success', 'Berhasil login dengan Google!');
            }
        }

        return redirect()->route('login')->withErrors(['email' => 'Verifikasi akun Google gagal atau token tidak valid.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    private function redirectPathByRole(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'freelancer' => route('freelancer.dashboard'),
            'company' => route('company.dashboard'),
            default => '/',
        };
    }
}