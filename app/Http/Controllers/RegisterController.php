<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\CompanyAccountRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegister(): View
    {
        return view('auth.register');
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

            // Simpan user company dengan role = company (harusnya)
            $user = User::create([
                'name' => $data['name'],
                'email' => $email,
                'password' => Hash::make((string) $data['password']),
                'role' => 'company',
            ]);

            CompanyAccountRequest::create([
                'company_name' => $data['company_name'],
                'contact_person' => $data['name'],
                'company_email' => $email,
                'company_phone' => $data['company_phone'],
                'company_address' => $data['company_address'],
                'company_description' => $data['company_description'] ?? null,
                'request_status' => 'menunggu',
                'reviewed_by' => null,
                'note' => null,
            ]);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil. Akun perusahaan Anda sedang menunggu persetujuan Admin.');
        }

        // Freelancer register langsung aktif
        User::create([
            'name' => $data['name'],
            'email' => $email,
            'password' => Hash::make((string) $data['password']),
            'role' => 'freelancer',
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil. Silakan login.');
    }
}

