<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isCompany = $this->boolean('is_company');

        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'password' => ['required','string','min:8'],
            'password_confirmation' => ['required','same:password'],
            'is_company' => ['nullable','boolean'],

            // freelancer / general user phone field
            'phone' => [$isCompany ? 'nullable' : 'required', 'string', 'max:255'],

            // company fields
            'company_name' => [$isCompany ? 'required' : 'nullable','string','max:255'],
            'company_phone' => [$isCompany ? 'required' : 'nullable','string','max:255'],
            'company_address' => [$isCompany ? 'required' : 'nullable','string'],
            'company_description' => ['nullable','string'],

            // Consent fields
            'terms_accepted' => ['required', 'accepted'],
            'privacy_accepted' => ['required', 'accepted'],
            'usage_accepted' => ['required', 'accepted'],
            'marketing_accepted' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'terms_accepted.required' => 'Anda harus menyetujui Syarat & Ketentuan untuk mendaftar.',
            'terms_accepted.accepted' => 'Anda harus menyetujui Syarat & Ketentuan untuk mendaftar.',
            'privacy_accepted.required' => 'Anda harus menyetujui Kebijakan Privasi untuk mendaftar.',
            'privacy_accepted.accepted' => 'Anda harus menyetujui Kebijakan Privasi untuk mendaftar.',
            'usage_accepted.required' => 'Anda harus menyetujui Kebijakan Penggunaan Platform untuk mendaftar.',
            'usage_accepted.accepted' => 'Anda harus menyetujui Kebijakan Penggunaan Platform untuk mendaftar.',
        ];
    }
}

