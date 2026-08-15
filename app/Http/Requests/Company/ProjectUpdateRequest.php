<?php

namespace App\Http\Requests\Company;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi update project.
     *
     * Semua field didukung agar konsisten dengan Create Project.
     * Namun field yang terkunci (karena workflow) akan di-strip/diabaikan
     * oleh controller (backend = source of truth), bukan hanya di-disable di UI.
     */
    public function rules(): array
    {
        return [
            'project_name' => ['required', 'string', 'max:255'],

            'project_description' => ['nullable', 'string'],

            'category_id' => ['nullable', 'integer', 'exists:categories,id'],

            'budget' => ['nullable', 'numeric'],

            'deadline' => ['nullable', 'date'],

            'skills' => ['nullable', 'string'],

            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'attachment' => ['nullable', 'mimes:pdf,doc,docx,zip,rar', 'max:10240'],

            'status' => ['nullable', Rule::in(Project::STATUSES)],
        ];
    }
}
