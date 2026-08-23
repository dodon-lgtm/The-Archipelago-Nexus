<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form minta email reset password.
     */
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses kirim OTP ke email.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = Str::lower(trim($request->input('email')));
        $user = User::where('email', $email)->first();

        // Generate 6-digit OTP
        $otp = random_int(100000, 999999);
        $otpHash = Hash::make($otp);

        // Hapus OTP lama untuk email ini
        PasswordResetOtp::where('email', $email)->delete();

        // Simpan OTP ke database
        $otpRecord = PasswordResetOtp::create([
            'user_id'    => $user ? $user->id : null,
            'email'      => $email,
            'otp_hash'   => $otpHash,
            'expires_at' => now()->addMinutes(5),
            'attempts'   => 0,
        ]);

        // Simpan ke session (disesuaikan agar cocok dengan Blade)
        $request->session()->put('password_reset_otp_id', $otpRecord->id);
        $request->session()->put('password_reset_user_id', $user ? $user->id : null);
        $request->session()->put('password_reset_email', $email);
        $request->session()->put('password_reset_otp_sent_at', now()->timestamp);
        
        // Key kompatibilitas untuk Blade
        $request->session()->put('otp_email', $email);
        $request->session()->put('otp_id', $otpRecord->id);

        // Kirim OTP via email
        try {
            Mail::to($email)->send(new \App\Mail\PasswordResetOtpMail(
                (string) $otp,
                $user ? $user->name : 'Pengguna',
                config('app.name', 'ApexForge')
            ));
        } catch (\Exception $e) {
            Log::error('OTP Email Gagal Kirim: ' . $e->getMessage());

            // Tetap izinkan lanjut ke halaman OTP di Local Dev jika email gagal terkirim (OTP tertera di log)
            if (config('app.env') === 'local') {
                Log::info("KODE OTP LOCAL DEV UNTUK {$email}: {$otp}");
            } else {
                return back()
                    ->withInput()
                    ->with('error', 'Gagal mengirim email OTP. Pastikan konfigurasi email di .env sudah benar.');
            }
        }

        // Redirect ke halaman verifikasi OTP
        return redirect()->route('password.verify')
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    /**
     * Tampilkan form input verifikasi OTP.
     */
    public function showVerifyForm()
    {
        $otpId = session('password_reset_otp_id') ?? session('otp_id');

        if (!$otpId) {
            return redirect()->route('password.request')
                ->with('error', 'Silakan masukkan email Anda terlebih dahulu.');
        }

        return view('auth.verify-otp');
    }

    /**
     * Proses verifikasi OTP.
     */
    public function verifyOtp(Request $request)
    {
        // DEBUG SEMENTARA (langkah 2) — log request yang benar-benar masuk.
        Log::info('VERIFY OTP REQUEST', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'otp' => $request->input('otp'),
            'otp_digit_1' => $request->input('otp_digit_1'),
            'otp_digit_2' => $request->input('otp_digit_2'),
            'otp_digit_3' => $request->input('otp_digit_3'),
            'otp_digit_4' => $request->input('otp_digit_4'),
            'otp_digit_5' => $request->input('otp_digit_5'),
            'otp_digit_6' => $request->input('otp_digit_6'),
            'session_otp_id' => session('password_reset_otp_id') ?? session('otp_id'),
            'session_email' => session('password_reset_email') ?? session('otp_email'),
        ]);

        // Penggabungan 6 digit input jika Blade mengirimkan otp_digit_1..6.
        // Hanya angka yang dipertimbangkan, sehingga data yang di-paste / diberi
        // spasi tidak membuat validasi 'size:6' gagal secara diam-diam.
        if (!$request->has('otp') && $request->has('otp_digit_1')) {
            $combinedOtp = '';
            for ($i = 1; $i <= 6; $i++) {
                $combinedOtp .= preg_replace('/\D+/', '', (string) $request->input('otp_digit_' . $i, ''));
            }
            $request->merge(['otp' => $combinedOtp]);
        } else {
            $combinedOtp = (string) $request->input('otp', '');
        }

        // DEBUG SEMENTARA (langkah 3) — hasil gabungan OTP.
        Log::info('VERIFY OTP COMBINED', [
            'otp' => $combinedOtp,
            'otp_length' => strlen($combinedOtp),
        ]);

        $request->validate([
            'otp' => ['required', 'digits:6'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi lengkap 6 digit.',
            'otp.digits'   => 'Kode OTP harus berjumlah 6 digit angka.',
        ]);

        $otpId = session('password_reset_otp_id') ?? session('otp_id');
        $email = session('password_reset_email') ?? session('otp_email');

        if (!$otpId || !$email) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi telah berakhir. Silakan minta kode OTP ulang.');
        }

        $otpRecord = PasswordResetOtp::where('id', $otpId)
            ->where('email', $email)
            ->first();

        if (!$otpRecord) {
            return redirect()->route('password.verify')
                ->with('otp_invalid', 'Kode OTP tidak ditemukan atau telah kedaluwarsa.');
        }

        // Cek Expired
        if (now()->greaterThan($otpRecord->expires_at)) {
            return redirect()->route('password.verify')
                ->with('otp_expired', 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang kode.');
        }

        // Cek Percobaan Maksimal
        if ($otpRecord->attempts >= 5) {
            return redirect()->route('password.verify')
                ->with('too_many_attempts', 'Terlalu banyak percobaan salah. Silakan kirim ulang kode baru.');
        }

        // Verifikasi Hash OTP

        // DEBUG SEMENTARA (langkah 4) — info record OTP (TANPA otp_hash).
        Log::info('VERIFY OTP RECORD', [
            'otp_id' => $otpRecord->id,
            'email' => $otpRecord->email,
            'expires_at' => $otpRecord->expires_at->toDateTimeString(),
            'attempts' => $otpRecord->attempts,
        ]);

        if (!Hash::check($request->input('otp'), $otpRecord->otp_hash)) {
            $otpRecord->increment('attempts');
            $remaining = 5 - $otpRecord->attempts;

            return redirect()->route('password.verify')
                ->with('otp_invalid', "Kode OTP salah. Sisa percobaan: {$remaining}");
        }

        // OTP Benar: tandai sebagai terverifikasi.
        $otpRecord->verified_at = now();
        $otpRecord->save();

        $user = $otpRecord->user_id
            ? User::find($otpRecord->user_id)
            : User::where('email', $otpRecord->email)->first();

        // Pastikan SEMUA key session yang dibutuhkan halaman reset tersimpan,
        // sehingga tidak ada dua sistem session key yang saling bertabrakan.
        $request->session()->put('password_reset_verified', true);
        $request->session()->put('password_reset_otp_id', $otpRecord->id);
        $request->session()->put('password_reset_email', $otpRecord->email);
        $request->session()->put('password_reset_user_id', $user ? $user->id : null);
        $request->session()->put('otp_id', $otpRecord->id);
        $request->session()->put('otp_email', $otpRecord->email);

        $request->session()->save();

        // DEBUG SEMENTARA (langkah 5) — tepat sebelum redirect sukses.
        Log::info('VERIFY OTP SUCCESS - REDIRECT RESET', [
            'otp_id' => $otpRecord->id,
            'user_id' => $otpRecord->user_id,
            'session_verified_before_redirect' => session('password_reset_verified'),
        ]);

        return redirect()->route('password.reset')
            ->with('status', 'OTP berhasil diverifikasi. Silakan buat password baru.');
    }

    /**
     * Proses kirim ulang OTP.
     */
    public function resendOtp(Request $request)
    {
        $otpId = session('password_reset_otp_id') ?? session('otp_id');
        $email = session('password_reset_email') ?? session('otp_email');

        if (!$otpId || !$email) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi tidak ditemukan. Silakan masukkan email kembali.');
        }

        // Cooldown 60 Detik
        $lastSent = session('password_reset_otp_sent_at', 0);
        if (now()->timestamp - $lastSent < 60) {
            $remaining = 60 - (now()->timestamp - $lastSent);
            return back()->with('resend_countdown', $remaining);
        }

        $otpRecord = PasswordResetOtp::find($otpId);
        $user = $otpRecord ? User::find($otpRecord->user_id) : User::where('email', $email)->first();

        // Generate OTP Baru
        $otp = random_int(100000, 999999);
        $otpHash = Hash::make($otp);

        if ($otpRecord) {
            $otpRecord->update([
                'otp_hash'   => $otpHash,
                'expires_at' => now()->addMinutes(5),
                'attempts'   => 0,
                'verified_at' => null,
            ]);
        } else {
            $otpRecord = PasswordResetOtp::create([
                'user_id'    => $user ? $user->id : null,
                'email'      => $email,
                'otp_hash'   => $otpHash,
                'expires_at' => now()->addMinutes(5),
                'attempts'   => 0,
            ]);
        }

        $request->session()->put('password_reset_otp_id', $otpRecord->id);
        $request->session()->put('password_reset_otp_sent_at', now()->timestamp);
        $request->session()->put('otp_id', $otpRecord->id);

        try {
            Mail::to($email)->send(new \App\Mail\PasswordResetOtpMail(
                (string) $otp,
                $user ? $user->name : 'Pengguna',
                config('app.name', 'ApexForge')
            ));
        } catch (\Exception $e) {
            Log::error('OTP Resend Gagal: ' . $e->getMessage());
        }

        return back()->with('status', 'Kode OTP baru telah dikirim ke email.');
    }

    /**
     * Tampilkan form reset password baru.
     */
    public function showResetForm()
    {
        // Hanya boleh dibuka setelah OTP benar-benar diverifikasi pada sesi ini,
        // tanpa perlu login terlebih dahulu.
        $verified = session('password_reset_verified');
        $userId = session('password_reset_user_id');
        $otpId = session('password_reset_otp_id') ?? session('otp_id');

        if ($verified !== true || !$userId || !$otpId) {
            return redirect()->route('password.request')
                ->with('error', 'Silakan verifikasi OTP terlebih dahulu.');
        }

        return view('auth.reset-password');
    }

    /**
     * Simpan password baru.
     */
    public function resetPassword(Request $request)
    {
        if (!session('password_reset_verified')) {
            return redirect()->route('password.request')
                ->with('error', 'Silakan verifikasi OTP terlebih dahulu.');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $userId = session('password_reset_user_id');
        $email = session('password_reset_email');

        $user = $userId ? User::find($userId) : User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'Akun pengguna tidak ditemukan.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Invalidasi & hapus record OTP agar kode tidak dapat dipakai ulang.
        if ($userId) {
            PasswordResetOtp::where('user_id', $userId)->delete();
        } else {
            PasswordResetOtp::where('email', $email)->delete();
        }

        // Hapus seluruh session reset password
        $request->session()->forget([
            'password_reset_user_id',
            'password_reset_otp_id',
            'password_reset_email',
            'password_reset_verified',
            'password_reset_otp_sent_at',
            'otp_email',
            'otp_id',
        ]);

        return redirect()->route('login')
            ->with('status', 'Password berhasil diubah. Silakan login.');
    }
}