<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'position' => 'required|string'
        ];
        if (gettype(request()->id) === 'integer') {
            $rules['id'] = 'required|integer';
        } else {
            $rules['id'] = 'required|string';
        }
        return $rules;
    }
}
