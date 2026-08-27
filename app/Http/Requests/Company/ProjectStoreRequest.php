<?php

namespace App\Http\Requests\Company;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

            'status' => ['required', Rule::in(Project::STATUSES)],

            // Tahap Pengerjaan (REVISI): boleh kosong, tapi bila diisi harus
            // berupa array paralel stage_name[] / stage_desc[].
            'stage_name' => ['nullable', 'array'],
            'stage_name.*' => ['nullable', 'string', 'max:255'],
            'stage_desc' => ['nullable', 'array'],
            'stage_desc.*' => ['nullable', 'string', 'max:2000'],

        ];
    }
}
