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
            'project_name' => ['sometimes', 'string', 'max:255'],

            'project_description' => ['sometimes', 'nullable', 'string'],

            'category_id' => ['sometimes', 'nullable', 'integer', 'exists:categories,id'],

            'budget' => ['sometimes', 'nullable', 'numeric'],

            'deadline' => ['sometimes', 'nullable', 'date', 'after_or_equal:today'],

            'skills' => ['sometimes', 'nullable', 'string'],

            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'attachment' => ['sometimes', 'nullable', 'mimes:pdf,doc,docx,zip,rar', 'max:10240'],

            'status' => ['sometimes', Rule::in(Project::STATUSES)],
        ];
    }
}
