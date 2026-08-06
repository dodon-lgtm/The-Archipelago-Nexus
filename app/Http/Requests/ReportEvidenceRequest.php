<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi unggah bukti tambahan untuk laporan berstatus 'menunggu-bukti'.
 */
class ReportEvidenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attachments'   => 'required|array|min:1|max:5',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'attachments.required'    => 'Anda harus mengunggah minimal satu bukti.',
            'attachments.min'         => 'Anda harus mengunggah minimal satu bukti.',
            'attachments.max'         => 'Maksimal 5 file bukti.',
            'attachments.*.mimes'     => 'Bukti hanya boleh berformat JPG, JPEG, PNG, atau PDF.',
            'attachments.*.max'       => 'Ukuran file bukti maksimal 5 MB.',
        ];
    }
}
