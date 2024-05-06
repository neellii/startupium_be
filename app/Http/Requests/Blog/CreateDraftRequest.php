<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class CreateDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'string'],
            'title' => ['required', 'string'],
            'author' => ['required', 'array', 'min:3'],
        ];
    }

    protected function prepareForValidation() :void
    {
        $this->merge([
            'slug' => $this->slug ? $this->slug : generateDraftSlug(),
            'subjects' => json_decode($this->subjects, true) ?? [],
            'author' => json_decode($this->author, true) ?? [],
        ]);

    }

    public function messages()
    {
        return [
            'title.string' => 'Название блога должно быть строкой',
            'title.required' => 'Поле Название обязательно для заполнения.',
        ];
    }
}
