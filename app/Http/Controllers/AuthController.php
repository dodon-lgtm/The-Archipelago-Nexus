<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                    ->where(
                        'company_email',
                        $user->email
                    )
                    ->where(
                        'request_status',
                        'disetujui'
                    )
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
        // LOGIN BIASA
        // =========================================================
        //
        // Kalau tidak ada session tujuan penawaran,
        // user diarahkan ke dashboard berdasarkan role.
        //

        return redirect()->intended(
            $this->redirectPathByRole($user)
        );
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