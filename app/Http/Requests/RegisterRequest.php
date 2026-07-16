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

            // freelancer fields
            // (none)

            // company fields
            'company_name' => [$isCompany ? 'required' : 'nullable','string','max:255'],
            'company_phone' => [$isCompany ? 'required' : 'nullable','string','max:255'],
            'company_address' => [$isCompany ? 'required' : 'nullable','string'],
            'company_description' => [$isCompany ? 'nullable' : 'nullable','string'],

            // ensure company email unique vs users
            // uniqueness rule for users.email is handled implicitly in validator below
        ];
    }
}

