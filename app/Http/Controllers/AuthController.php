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

