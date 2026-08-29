<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FinancialSettingUpdateRequest — validasi server-side pengaturan keuangan.
 *
 * Semua validasi di sini (JANGAN percaya frontend):
 *   - project_fee_rate      : 0..100, maks 2 desimal
 *   - withdrawal_fee_rate   : 0..100, maks 2 desimal
 *   - free_project_uploads  : integer >= 0
 *   - paid_project_upload_price : numeric >= 0, maks 2 desimal
 */
class FinancialSettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->role === 'admin';
    }

    /** Regex: angka dengan maksimal 2 angka desimal (string rule, tanpa wrapper array). */
    private const RATE_RULE = 'required|numeric|min:0|max:100|regex:/^\d{1,3}(\.\d{1,2})?$/';
    private const PRICE_RULE = 'required|numeric|min:0|regex:/^\d{1,13}(\.\d{1,2})?$/';

    public function rules(): array
    {
        // PENTING: nilai berupa string langsung (BUKAN [self::RATE_RULE]).
        // String pipe-rule yang dibungkus array tidak di-explode oleh Validator.
        return [
            'project_fee_rate' => self::RATE_RULE,
            'withdrawal_fee_rate' => self::RATE_RULE,
            'free_project_uploads_per_month' => 'required|integer|min:0',
            'paid_project_upload_price' => self::PRICE_RULE,
            'paid_project_upload_price_raw' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'project_fee_rate.required' => 'Persentase fee platform proyek wajib diisi.',
            'project_fee_rate.numeric' => 'Persentase fee platform proyek harus berupa angka.',
            'project_fee_rate.min' => 'Persentase fee platform proyek tidak boleh negatif.',
            'project_fee_rate.max' => 'Persentase fee platform proyek maksimal 100.',
            'project_fee_rate.regex' => 'Persentase fee platform proyek maksimal 2 angka desimal.',
            'withdrawal_fee_rate.required' => 'Persentase fee withdrawal wajib diisi.',
            'withdrawal_fee_rate.numeric' => 'Persentase fee withdrawal harus berupa angka.',
            'withdrawal_fee_rate.min' => 'Persentase fee withdrawal tidak boleh negatif.',
            'withdrawal_fee_rate.max' => 'Persentase fee withdrawal maksimal 100.',
            'withdrawal_fee_rate.regex' => 'Persentase fee withdrawal maksimal 2 angka desimal.',
            'free_project_uploads_per_month.required' => 'Batas upload gratis wajib diisi.',
            'free_project_uploads_per_month.integer' => 'Batas upload gratis harus berupa bilangan bulat.',
            'free_project_uploads_per_month.min' => 'Batas upload gratis tidak boleh negatif.',
            'paid_project_upload_price.required' => 'Harga upload setelah kuota habis wajib diisi.',
            'paid_project_upload_price.numeric' => 'Harga upload harus berupa angka.',
            'paid_project_upload_price.min' => 'Harga upload tidak boleh negatif.',
            'paid_project_upload_price.regex' => 'Harga upload maksimal 2 angka desimal.',
        ];
    }
}
