<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request terpusat untuk membuat report (V3).
 *
 * Dipakai oleh ReportController (generic), Company\ReportController, dan
 * Freelancer\ReportController sehingga validasi tidak terduplikasi.
 *
 * Catatan V3:
 *  - `target` TIDAK diambil dari form; ditentukan oleh ReportService
 *    (backend source of truth) dari konteks yang dikirim.
 *  - `category` wajib & divalidasi terhadap target oleh ReportService.
 *  - `attachments` opsional: maks 5 file, tipe jpg/jpeg/png/pdf, maks 5MB.
 */
class ReportStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject'          => 'required|string|max:255',
            'description'      => 'required|string|max:5000',
            'category'         => 'required|string|in:' . implode(',', Report::categories()),
            'reported_user_id' => 'nullable|exists:users,id',
            'project_id'       => 'nullable|exists:projects,id',
            'penawaran_id'     => 'nullable|exists:penawarans,id',
            'workspace_id'     => 'nullable|exists:project_workspaces,id',
            'attachments'      => 'nullable|array|max:5',
            'attachments.*'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required'     => 'Subjek laporan wajib diisi.',
            'description.required' => 'Deskripsi laporan wajib diisi.',
            'category.required'    => 'Kategori laporan wajib dipilih.',
            'category.in'          => 'Kategori laporan tidak valid.',
            'attachments.max'      => 'Maksimal 5 file bukti.',
            'attachments.*.mimes'  => 'Bukti hanya boleh berformat JPG, JPEG, PNG, atau PDF.',
            'attachments.*.max'    => 'Ukuran file bukti maksimal 5 MB.',
        ];
    }
}
