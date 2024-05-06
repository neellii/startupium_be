<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class YandexSignInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'yandexID' => ['required', 'string'],
            'psuID' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Все поля должны быть заполнены.',
            'psuID.required' => 'Все поля должны быть заполнены.',
            'yandexID.required' => 'Все поля должны быть заполнены.',
        ];
    }
}
