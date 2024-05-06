<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];
        if (gettype(request()->id) === 'integer') {
            $rules['id'] = 'required|integer';
        } else {
            $rules['id'] = 'required|string';
        }
        if (gettype(request()->subscriber) === 'integer') {
            $rules['subscriber'] = 'required|integer';
        } else {
            $rules['subscriber'] = 'required|string';
        }
        return $rules;
    }
}