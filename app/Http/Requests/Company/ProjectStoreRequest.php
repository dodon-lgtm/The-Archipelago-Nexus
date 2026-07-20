<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

   public function rules(): array
{
    return [

        'project_name' => ['required', 'string', 'max:255'],

        'project_description' => ['required', 'string'],

        'category_id' => ['nullable', 'integer', 'exists:categories,id'],

        'budget' => ['required', 'numeric'],

        'deadline' => ['required', 'date'],

        'skills' => ['required', 'string'],

        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

        'attachment' => ['nullable', 'mimes:pdf,doc,docx,zip,rar', 'max:10240'],

        'status' => ['required', 'in:Open,Closed'],

    ];
}
}

