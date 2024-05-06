<?php

namespace App\Http\Requests\Carrer;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCarrerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => ['required', 'string']
        ];
    }
}
