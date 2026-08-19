<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\CompanyAccountRequest;
use App\Models\CompanyProfile;
use App\Models\User;
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
        User::create([
            'name'     => $data['name'], 
            'email'    => $email,
            'phone'    => $data['phone'],
            'password' => Hash::make((string) $data['password']),
            'role'     => 'freelancer',
        ]);

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
}