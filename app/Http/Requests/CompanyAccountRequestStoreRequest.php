<?php

namespace App\Http\Requests;

use App\Models\CompanyAccountRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;


class CompanyAccountRequestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $email = $this->input('company_email');

        return [
            'company_name' => ['required','string','max:255'],
            'contact_person' => ['required','string','max:255'],
            'company_email' => [
                'required',
                'email',
                'max:255',
                // email tidak boleh sama dengan email user terdaftar
                function (string $attribute, mixed $value, $fail) {
                    $exists = \App\Models\User::query()->where('email', $value)->exists();
                    if ($exists) {
                        $fail('Email perusahaan sudah terdaftar pada akun pengguna.');
                    }
                },
                // email tidak boleh memiliki permintaan aktif status menunggu
                function (string $attribute, mixed $value, $fail) {
                    $existsActive = CompanyAccountRequest::query()
                        ->where('company_email', $value)
                        ->where('request_status', 'menunggu')
                        ->exists();

                    if ($existsActive) {
                        $fail('Email perusahaan masih memiliki permintaan yang belum diproses.');
                    }
                },
            ],
            'company_phone' => ['required','string','max:255'],
            'company_address' => ['required','string'],
            'company_description' => ['nullable','string'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Nama perusahaan wajib diisi.',
            'contact_person.required' => 'Nama penanggung jawab wajib diisi.',
            'company_email.required' => 'Email perusahaan wajib diisi.',
            'company_email.email' => 'Format email perusahaan tidak valid.',
            'company_phone.required' => 'Nomor telepon wajib diisi.',
            'company_address.required' => 'Alamat perusahaan wajib diisi.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('company_email')) {
            $this->merge([
                'company_email' => Str::lower((string) $this->input('company_email')),
            ]);
        }
    }
}

