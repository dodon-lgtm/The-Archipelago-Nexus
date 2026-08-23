<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FooterSettingUpdateRequest extends FormRequest
{
    /** Hanya admin yang dapat mengelola pengaturan footer. */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'privacy_policy_content'   => ['nullable', 'string'],
            'terms_conditions_content' => ['nullable', 'string'],
            'support_email'            => ['nullable', 'email', 'max:191'],
            'about_text'               => ['nullable', 'string'],
            'copyright_text'           => ['nullable', 'string', 'max:191'],
        ];
    }

    public function messages(): array
    {
        return [
            'support_email.email'    => 'Email dukungan harus berupa alamat email yang valid.',
            'support_email.max'      => 'Email dukungan maksimal 191 karakter.',
            'copyright_text.max'     => 'Teks hak cipta maksimal 191 karakter.',
        ];
    }
}