<?php

namespace App\Http\Requests\Admin;

use App\Enums\ContentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdminOrEditor();
    }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('trainings', 'slug')->ignore($courseId)],
            'description' => ['required', 'string'],
            'status' => ['required', Rule::in(ContentStatus::values())],
            'is_premium_only' => ['boolean'],
            'meeting_url' => ['nullable', 'url', 'max:500'],
            'resources_url' => ['nullable', 'url', 'max:500'],
            'published_at' => ['nullable', 'date'],
            'thumbnail' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_premium_only' => $this->boolean('is_premium_only'),
        ]);
    }
}
