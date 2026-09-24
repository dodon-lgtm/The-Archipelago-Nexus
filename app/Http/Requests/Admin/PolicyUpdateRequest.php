<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PolicyUpdateRequest extends FormRequest
{
    /** Hanya admin yang dapat mengelola dokumen kebijakan. */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $policy = $this->route('policy');

        return [
            'title'     => ['required', 'string', 'max:191'],
            'slug'      => ['required', 'string', 'max:50', 'alpha_dash', 'regex:/^[a-z0-9_-]+$/', Rule::unique('policies', 'slug')->ignore($policy?->id)],
            'content'   => ['required', 'string'],
            'version'   => ['required', 'string', 'max:20', 'regex:/^\d+(\.\d+)*$/'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'Judul kebijakan wajib diisi.',
            'title.max'        => 'Judul kebijakan maksimal 191 karakter.',
            'slug.required'    => 'Slug kebijakan wajib diisi.',
            'slug.max'         => 'Slug kebijakan maksimal 50 karakter.',
            'slug.alpha_dash'  => 'Slug hanya boleh berisi huruf kecil, angka, strip, dan underscore.',
            'slug.regex'       => 'Format slug tidak valid.',
            'slug.unique'      => 'Slug sudah digunakan, gunakan slug lain.',
            'content.required' => 'Isi kebijakan wajib diisi.',
            'version.required' => 'Versi kebijakan wajib diisi.',
            'version.max'      => 'Versi kebijakan maksimal 20 karakter.',
            'version.regex'    => 'Format versi tidak valid (contoh: 1.0, 1.1, 2.0.1).',
        ];
    }
}
