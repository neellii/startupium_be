<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplainRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reason' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'reason.string' => 'Поле причина должно быть строкой',
            'reason.required' => 'Поле причина обязательно для заполнения.',
        ];
    }
}
