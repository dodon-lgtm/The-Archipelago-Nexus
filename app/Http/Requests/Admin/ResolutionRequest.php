<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi request untuk resolusi workspace Admin.
 *
 * - action: release_to_freelancer | refund_to_company
 * - reason: wajib diisi (min 10 karakter)
 * - deadline: opsional
 */
class ResolutionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action'          => ['required', 'string', 'in:release_to_freelancer,refund_to_company'],
            'reason'          => ['required', 'string', 'min:10'],
            'deadline'        => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required'       => 'Aksi keputusan wajib dipilih.',
            'action.in'             => 'Aksi keputusan tidak valid.',
            'reason.required'       => 'Alasan keputusan wajib diisi.',
            'reason.min'            => 'Alasan keputusan minimal 10 karakter.',
            'deadline.max'          => 'Deadline maksimal 255 karakter.',
        ];
    }
}