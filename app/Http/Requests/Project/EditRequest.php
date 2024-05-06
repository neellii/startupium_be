<?php
namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:500|min:3',
        ];
    }

    public function messages()
    {
        return [
            'title.min' => 'Количество символов в названии проекта должно быть не менее 3.',
            'title.max' => 'Количество символов в названии проекта не может превышать 500.',
            'slug.max' => 'Количество символов в названии проекта не может превышать 500.',
        ];
    }
}
