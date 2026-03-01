<?php

namespace App\Http\Requests\Admin;

use App\Enums\ContentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdminOrEditor();
    }

    public function rules(): array
    {
        $trainingId = $this->route('course')?->id;
        $lessonId = $this->route('lesson')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('lessons', 'slug')
                    ->where('training_id', $trainingId)
                    ->ignore($lessonId),
            ],
            'content' => ['nullable', 'string'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', Rule::in(ContentStatus::values())],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_preview' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_preview' => $this->boolean('is_preview'),
        ]);
    }
}
