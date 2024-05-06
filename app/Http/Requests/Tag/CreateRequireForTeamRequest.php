<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequireForTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3'
        ];
    }

    public function messages()
    {
        return [
            'title.string' => 'Поле специальность должно быть строкой',
            'title.required' => 'Поле специальность обязательно для заполнения.',
            'title.min' => 'Поле специальность должно состоять минимум из 3 символов.',
        ];
    }
}
