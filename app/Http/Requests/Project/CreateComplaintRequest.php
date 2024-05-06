<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplaintRequest extends FormRequest
{
    public function authorize(): bool
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
