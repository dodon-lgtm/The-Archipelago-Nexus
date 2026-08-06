<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validasi terpusat untuk update status laporan oleh Admin (V3).
 *
 * Workflow 5 status:
 *   menunggu -> ditinjau -> menunggu-bukti / selesai / ditolak
 *   menunggu-bukti -> ditinjau (setelah reporter mengunggah bukti)
 */
class ReportUpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'     => 'required|in:ditinjau,menunggu-bukti,selesai,ditolak',
            'admin_note' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status laporan wajib dipilih.',
            'status.in'       => 'Status laporan tidak valid.',
        ];
    }
}
