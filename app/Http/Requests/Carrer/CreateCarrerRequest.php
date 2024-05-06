<?php

namespace App\Http\Requests\Carrer;

use Illuminate\Foundation\Http\FormRequest;

class CreateCarrerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'career' => ['required', 'string']
        ];
    }
}
