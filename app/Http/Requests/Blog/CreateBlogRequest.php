<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class CreateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subjects' => ['required', 'array', 'min:1', 'max:1'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'slug' => ['string', 'max:500', 'required'],
            'author' => ['required', 'array', 'min:3'],
        ];
    }

    protected function prepareForValidation() :void
    {
        $this->merge([
            'slug' => generateSlug($this->title, 500),
            'subjects' => json_decode($this->subjects, true) ?? [],
            'author' => json_decode($this->author, true) ?? []
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
