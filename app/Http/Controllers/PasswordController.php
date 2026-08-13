<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    /**
     * ==========================================================
     * VERIFIKASI PASSWORD LAMA
     * ==========================================================
     */
    public function verifyCurrentPassword(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required',
                'string',
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {

            return response()->json([
                'success' => false,
                'message' => 'Password saat ini salah.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN STATUS VERIFIKASI SEMENTARA
        |--------------------------------------------------------------------------
        |
        | Tidak menyimpan password lama.
        | Hanya menyimpan tanda bahwa password sudah berhasil diverifikasi.
        |
        */

        session([
            'password_change_verified' => true,
            'password_change_verified_at' => now()->timestamp,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password lama berhasil diverifikasi.',
        ]);
    }


    /**
     * ==========================================================
     * UPDATE PASSWORD BARU
     * ==========================================================
     */
    public function updatePassword(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH PASSWORD LAMA SUDAH DIVERIFIKASI
        |--------------------------------------------------------------------------
        */

        $verified = session('password_change_verified', false);

        $verifiedAt = session(
            'password_change_verified_at',
            0
        );

        /*
        |--------------------------------------------------------------------------
        | VERIFIKASI HANYA BERLAKU 5 MENIT
        |--------------------------------------------------------------------------
        */

        $isStillValid =
            $verified &&
            ($verifiedAt + 300) >= now()->timestamp;

        if (!$isStillValid) {

            session()->forget([
                'password_change_verified',
                'password_change_verified_at',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Verifikasi password telah kedaluwarsa. Silakan verifikasi kembali.',
            ], 403);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI PASSWORD BARU
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'new_password.required' =>
                'Password baru wajib diisi.',

            'new_password.min' =>
                'Password baru minimal 8 karakter.',

            'new_password.confirmed' =>
                'Konfirmasi password tidak cocok.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE PASSWORD
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        $user->password = Hash::make(
            $request->new_password
        );

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | HAPUS STATUS VERIFIKASI
        |--------------------------------------------------------------------------
        */

        session()->forget([
            'password_change_verified',
            'password_change_verified_at',
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ]);
    }
}