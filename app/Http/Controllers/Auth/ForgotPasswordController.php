<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Display the forgot password form.
     */
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle the forgot password form submission.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = Str::lower(trim($request->input('email')));

        // Cari user berdasarkan email (tidak revel akun apa adanya)
        $user = User::where('email', $email)->first();

        // Generate 6-digit OTP
        $otp = random_int(100000, 999999);
        $otpHash = Hash::make($otp);

        // Invalidate any existing OTP for this user/email
        PasswordResetOtp::where('email', $email)->delete();

        // Simpan OTP ke database
        $otpRecord = PasswordResetOtp::create([
            'user_id' => $user ? $user->id : null,
            'email' => $email,
            'otp_hash' => $otpHash,
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        // Simpan ke session (OTP hash id, bukan plaintext)
        $request->session()->put('password_reset_otp_id', $otpRecord->id);
        $request->session()->put('password_reset_user_id', $user ? $user->id : null);
        $request->session()->put('password_reset_email', $email);

        // Kirim OTP via email
        try {
            \Mail::to($email)->send(new \App\Mail\PasswordResetOtpMail(
                (string) $otp,
                $user ? $user->name : 'Pengguna',
                config('app.name', 'The Archipelago Nexus')
            ));
        } catch (\Exception $e) {
            // Log error tapi tetap tampilkan pesan sukses
            // untuk mencegah user enumeration
            \Log::error('OTP Email Gagal: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengirim email. Silakan coba lagi.');
        }

        // Redirect ke halaman verifikasi OTP
        return redirect()->route('password.verify')
            ->with('status', 'Jika email tersebut terdaftar, kode OTP telah dikirim.');
    }

    /**
     * Display the OTP verification form.
     */
    public function showVerifyForm()
    {
        $otpId = session('password_reset_otp_id');

        if (!$otpId) {
            return redirect()->route('password.request')
                ->with('error', 'Sesudah permintaan OTP, silakan verifikasi.');
        }

        return view('auth.verify-otp');
    }

    /**
     * Handle the OTP verification form submission.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'numeric'],
        ]);

        $otpId = session('password_reset_otp_id');
        $email = session('password_reset_email');

        if (!$otpId || !$email) {
            return redirect()->route('password.request')
                ->with('error', 'Sesudah permintaan OTP, silakan kirim ulang kode.');
        }

        // Cari OTP record
        $otpRecord = PasswordResetOtp::where('id', $otpId)
            ->where('email', $email)
            ->first();

        if (!$otpRecord) {
            return redirect()->route('password.verify')
                ->with('error', 'Kode OTP tidak valid atau telah expired.');
        }

        // Check expired
        if (now()->greaterThan($otpRecord->expires_at)) {
            // Mark as expired and invalidate
            $otpRecord->verified_at = now();
            $otpRecord->save();

            return redirect()->route('password.verify')
                ->with('otp_expired', 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang kode.');
        }

        // Check attempts limit
        if ($otpRecord->attempts >= 5) {
            // Invalidate the OTP
            $otpRecord->verified_at = now();
            $otpRecord->save();

            return redirect()->route('password.verify')
                ->with('too_many_attempts', 'Terlalu banyak percobaan. Kode OTP tidak dapat digunakan lagi. Silakan kirim ulang kode.');
        }

        // Verify OTP
        $otpPlain = $request->input('otp');

        if (!Hash::check($otpPlain, $otpRecord->otp_hash)) {
            // Increment attempts
            $otpRecord->increment('attempts');
            $remaining = 5 - $otpRecord->attempts;

            return redirect()->route('password.verify')
                ->with('otp_invalid', "Kode OTP salah. Percobaan tersisa: {$remaining}");
        }

        // OTP benar - tandai sebagai diverifikasi
        $otpRecord->verified_at = now();
        $otpRecord->save();

        // Simpan state di session bahwa user boleh reset password
        $request->session()->put('password_reset_verified', true);
        $request->session()->put('password_reset_user_id', $otpRecord->user_id);

        // Redirect ke halaman reset password
        return redirect()->route('password.reset.form')
            ->with('status', 'OTP berhasil diverifikasi. Silakan masukkan password baru.');
    }

    /**
     * Handle resend OTP request.
     */
    public function resendOtp(Request $request)
    {
        $otpId = session('password_reset_otp_id');
        $email = session('password_reset_email');

        if (!$otpId || !$email) {
            return back()
                ->with('error', 'Tidak ada permintaan OTP yang ditemukan. Silakan kirim email terlebih dahulu.');
        }

        // Check cooldown 60 detik
        $lastSent = session('password_reset_otp_sent_at', 0);

        if (now()->timestamp - $lastSent < 60) {
            $remaining = 60 - (now()->timestamp - $lastSent);
            return back()
                ->with('error', "Silakan kirim ulang kode dalam {$remaining} detik.");
        }

        // Cari OTP record lama dan invalidkan
        PasswordResetOtp::where('id', $otpId)->update([
            'verified_at' => now(),
        ]);

        // Generate OTP baru
        $user = $otpRecord ? User::find($otpRecord->user_id) : null;
        $otp = random_int(100000, 999999);
        $otpHash = Hash::make($otp);

        // Update OTP record
        $otpRecord = PasswordResetOtp::where('id', $otpId)->firstOrFail();
        $otpRecord->otp_hash = $otpHash;
        $otpRecord->expires_at = now()->addMinutes(5);
        $otpRecord->attempts = 0;
        $otpRecord->verified_at = null;
        $otpRecord->save();

        // Update session
        $request->session()->put('password_reset_otp_id', $otpRecord->id);
        $request->session()->put('password_reset_otp_sent_at', now()->timestamp);

        // Kirim OTP baru via email
        try {
            \Mail::to($email)->send(new \App\Mail\PasswordResetOtpMail(
                (string) $otp,
                $user ? $user->name : 'Pengguna',
                config('app.name', 'The Archipelago Nexus')
            ));
        } catch (\Exception $e) {
            \Log::error('OTP Resend Gagal: ' . $e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan saat mengirim ulang email. Silakan coba lagi.');
        }

        return back()
            ->with('status', 'Kode OTP baru telah dikirim ke email.');
    }

    /**
     * Display the reset password form.
     */
    public function showResetForm()
    {
        // Cek apakah user sudah diverifikasi OTP
        if (!session('password_reset_verified')) {
            return redirect()->route('password.request')
                ->with('error', 'Silakan verifikasi OTP terlebih dahulu.');
        }

        $userId = session('password_reset_user_id');

        if (!$userId) {
            return redirect()->route('password.request')
                ->with('error', 'Data sesi tidak valid.');
        }

        return view('auth.reset-password');
    }

    /**
     * Handle the reset password form submission.
     */
    public function resetPassword(Request $request)
    {
        // Cek verifikasi
        if (!session('password_reset_verified')) {
            return redirect()->route('password.request')
                ->with('error', 'Sesuai permintaan, silakan verifikasi OTP terlebih dahulu.');
        }

        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $userId = session('password_reset_user_id');

        if (!$userId) {
            return redirect()->route('password.request')
                ->with('error', 'Data sesi tidak valid.');
        }

        // Update password user
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'Akun tidak ditemukan.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Invalidate OTP - tandai verified_at dan bersihkan session
        $otpId = session('password_reset_otp_id');

        if ($otpId) {
            PasswordResetOtp::where('id', $otpId)->update([
                'verified_at' => now(),
            ]);
        }

        // Bersihkan session reset password
        $request->session()->forget([
            'password_reset_user_id',
            'password_reset_otp_id',
            'password_reset_email',
            'password_reset_verified',
        ]);

        return redirect()->route('login')
            ->with('status', 'Password berhasil diubah. Silakan login menggunakan password baru.');
    }
}