<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => [], // null or string
            'subjects' => [], // null or array
            'slug' => ['string', 'required'], // blog slug, that needs to be changed
            'title' => ['required', 'string'],
            'author' => ['required', 'array', 'min:3'],
        ];
    }

    protected function prepareForValidation() :void
    {
        $this->merge([
            'author' => json_decode($this->author, true) ?? [],
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
