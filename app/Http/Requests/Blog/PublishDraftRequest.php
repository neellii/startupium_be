<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class PublishDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subjects' => [], // null or json
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'slug' => ['string', 'max:500', 'required'],
        ];
    }

    protected function prepareForValidation() :void
    {
        $this->merge([
            'subjects' => json_decode($this->subjects, true) ?? [],
        ]);
    }

    public function messages()
    {
        return [
            'title.string' => 'Название блога должно быть строкой',
            'title.required' => 'Поле Название обязательно для заполнения.',
            'description.string' => 'Описание блога должно быть строкой',
            'description.required' => 'Поле Описание обязательно для заполнения.',
        ];
    }
}
