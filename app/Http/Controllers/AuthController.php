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
    /**
     * Menampilkan halaman login.
     *
     * Jika user datang dari tombol "Kirim Penawaran",
     * project_id akan disimpan ke session terlebih dahulu.
     */
    public function showLogin(Request $request): View
    {
        // Jika ada project yang ingin dikirim penawaran,
        // simpan ID project ke session.
        if ($request->filled('project')) {
            $request->session()->put(
                'offer_project_id',
                $request->input('project')
            );
        }

        // Simpan tujuan awal (intended destination) ke session
        // memakai mekanisme bawaan Laravel (session key "url.intended"
        // yang dibaca oleh redirect()->intended()).
        //
        // URL hanya diterima jika merupakan URL internal aplikasi,
        // sehingga tidak membuka open redirect vulnerability.
        $redirect = $request->input('redirect');
        if (is_safe_internal_url($redirect)) {
            $request->session()->put(
                'url.intended',
                $redirect
            );
        }

        return view('auth.login');
    }


    /**
     * Proses login.
     */
    public function login(Request $request): RedirectResponse
    {
        // =========================================================
        // VALIDASI LOGIN
        // =========================================================

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);


        // =========================================================
        // AMBIL DATA LOGIN
        // =========================================================

        $email = Str::lower(
            trim((string) $request->input('email'))
        );

        $password = (string) $request->input('password');


        // =========================================================
        // CARI USER
        // =========================================================

        $user = User::query()
            ->where('email', $email)
            ->first();


        // =========================================================
        // CEK EMAIL & PASSWORD
        // =========================================================

        if (!$user || !Hash::check($password, $user->password)) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Email atau password salah.',
                ]);
        }


        // =========================================================
        // ATURAN LOGIN COMPANY
        // COMPANY HARUS SUDAH DISETUJUI ADMIN
        // =========================================================

        if ($user->role === 'company') {

            $companyRequest =
                \App\Models\CompanyAccountRequest::query()
                ->where('company_email', $user->email)
                ->where('request_status', 'disetujui')
                ->first();


            if (!$companyRequest) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'email' =>
                            'Akun perusahaan Anda belum disetujui admin. Silakan menunggu persetujuan.',
                    ]);
            }
        }


        // =========================================================
        // LOGIN USER
        // =========================================================

        Auth::login(
            $user,
            $request->boolean('remember')
        );

        // Regenerate session setelah login
        $request->session()->regenerate();


        // =========================================================
        // REDIRECT KHUSUS KIRIM PENAWARAN
        // =========================================================
        //
        // Kalau sebelumnya user menekan:
        //
        // Detail Proyek
        //       ↓
        // Kirim Penawaran
        //       ↓
        // Login
        //
        // maka project ID sudah disimpan di:
        //
        // session('offer_project_id')
        //
        // Setelah login sebagai freelancer,
        // langsung arahkan ke form penawaran.
        //

        $offerProjectId = $request->session()->pull(
            'offer_project_id'
        );

        if (
            $offerProjectId &&
            $user->role === 'freelancer'
        ) {
            return redirect()->route(
                'freelancer.penawaran.create',
                [
                    'project' => $offerProjectId,
                ]
            );
        }


        // =========================================================
        // LOGIN DARI FLOW KIRIM PENAWARAN (redirect/intended)
        // =========================================================
        //
        // Kalau user datang dari:
        //
        // Detail Proyek → Kirim Penawaran → Login
        //
        // maka showLogin() sudah menyimpan URL tujuan ke
        // session "url.intended". Setelah login sebagai freelancer,
        // redirect()->intended() mengembalikan user ke tujuan awal.
        //
        // Hanya freelancer yang boleh diarahkan ulang ke tujuan ini.
        // Company/admin tetap ke dashboard masing-masing agar tidak
        // tersesat ke halaman yang hanya boleh diakses freelancer.
        //
        if ($user->role !== 'freelancer') {
            $request->session()->forget('url.intended');

            return redirect(
                $this->redirectPathByRole($user)
            );
        }

        return redirect()->intended(
            $this->redirectPathByRole($user)
        );
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
                    ]
                );

                // Cek khusus jika user berperan sebagai company
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

    /**
     * Logout.
     */
    
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }


    /**
     * Redirect berdasarkan role user.
     */
    private function redirectPathByRole(User $user): string
    {
        return match ($user->role) {

            'admin' =>
                route('admin.dashboard'),

            'freelancer' =>
                route('freelancer.dashboard'),

            'company' =>
                route('company.dashboard'),

            default =>
                '/',
        };
    }
}