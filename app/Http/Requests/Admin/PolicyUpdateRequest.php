<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PolicyUpdateRequest extends FormRequest
{
    /** Hanya admin yang dapat mengelola dokumen kebijakan. */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'title'     => ['required', 'string', 'max:191'],
            'content'   => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'Judul kebijakan wajib diisi.',
            'title.max'        => 'Judul kebijakan maksimal 191 karakter.',
            'content.required' => 'Isi kebijakan wajib diisi.',
                ];
    }
}
