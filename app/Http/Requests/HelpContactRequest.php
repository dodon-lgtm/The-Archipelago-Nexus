<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk form kontak "Hubungi Kami via Email"
 * pada halaman Pusat Bantuan ApexForge Labs.
 *
 * Kategori memakai daftar yang sudah disepakati untuk support email.
 */
class HelpContactRequest extends FormRequest
{
    public const CATEGORY_UMUM       = 'pertanyaan-umum';
    public const CATEGORY_AKUN       = 'masalah-akun';
    public const CATEGORY_PROYEK     = 'masalah-proyek';
    public const CATEGORY_WORKSPACE  = 'masalah-workspace';
    public const CATEGORY_TRANSAKSI  = 'masalah-transaksi';
    public const CATEGORY_BUG        = 'laporan-bug';
    public const CATEGORY_LAINNYA    = 'masalah-lainnya';

    /**
     * Kategori yang tampil pada dropdown form kontak.
     */
    public const CATEGORIES = [
        self::CATEGORY_UMUM,
        self::CATEGORY_AKUN,
        self::CATEGORY_PROYEK,
        self::CATEGORY_WORKSPACE,
        self::CATEGORY_TRANSAKSI,
        self::CATEGORY_BUG,
        self::CATEGORY_LAINNYA,
    ];

    public function authorize(): bool
    {
        // Halaman Pusat Bantuan bersifat publik (FAQ dapat diakses tamu),
        // jadi form kontak tetap boleh diakses tanpa login.
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', self::CATEGORIES)],
            'subject'  => ['required', 'string', 'max:255'],
            'message'  => ['required', 'string', 'max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama Lengkap wajib diisi.',
            'name.string'       => 'Nama Lengkap tidak valid.',
            'name.max'          => 'Nama Lengkap maksimal 255 karakter.',
            'email.required'    => 'Alamat Email wajib diisi.',
            'email.email'       => 'Format Alamat Email tidak valid.',
            'email.max'         => 'Alamat Email maksimal 255 karakter.',
            'category.required' => 'Kategori wajib dipilih.',
            'category.in'       => 'Kategori yang dipilih tidak valid.',
            'subject.required'  => 'Subjek pesan wajib diisi.',
            'subject.string'    => 'Subjek pesan tidak valid.',
            'subject.max'       => 'Subjek pesan maksimal 255 karakter.',
            'message.required'  => 'Pesan wajib diisi.',
            'message.string'    => 'Pesan tidak valid.',
            'message.max'       => 'Pesan maksimal 10.000 karakter.',
        ];
    }

    /**
     * Daftar kategori valid untuk validasi server-side.
     */
    public static function categories(): array
    {
        return self::CATEGORIES;
    }

    /**
     * Label tampilan sebuah kategori (untuk dropdown & isi email).
     */
    public static function categoryLabel(?string $category): string
    {
        return match ($category) {
            self::CATEGORY_UMUM      => 'Pertanyaan Umum',
            self::CATEGORY_AKUN      => 'Masalah Akun',
            self::CATEGORY_PROYEK    => 'Masalah Proyek',
            self::CATEGORY_WORKSPACE => 'Masalah Workspace',
            self::CATEGORY_TRANSAKSI => 'Masalah Transaksi',
            self::CATEGORY_BUG       => 'Laporan Bug',
            default                  => 'Masalah Lainnya',
        };
    }

    /**
     * Map [key => label] untuk dropdown pada Blade.
     */
    public static function categoriesWithLabels(): array
    {
        return array_combine(
            self::CATEGORIES,
            array_map(
                fn (string $key) => self::categoryLabel($key),
                self::CATEGORIES
            )
        );
    }
}