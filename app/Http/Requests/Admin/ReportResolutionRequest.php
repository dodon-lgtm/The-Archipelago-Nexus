<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi keputusan finansial Admin untuk dispute (release/refund/split).
 *
 * Admin WAJIB memberikan admin_note (alasan keputusan) dan, untuk split,
 * nominal untuk freelancer (sisa otomatis menjadi refund company).
 */
class ReportResolutionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'admin_note'       => ['required', 'string', 'max:2000'],
            'freelancer_amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'admin_note.required' => 'Catatan Admin wajib diisi sebagai alasan keputusan.',
            'admin_note.max'      => 'Catatan Admin maksimal 2000 karakter.',
            'freelancer_amount.numeric' => 'Nominal untuk freelancer harus berupa angka.',
            'freelancer_amount.min'     => 'Nominal untuk freelancer tidak boleh negatif.',
        ];
    }
}
