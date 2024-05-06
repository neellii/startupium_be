<?php

namespace App\Http\Requests\Communication;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];
        if (boolval(request()->cdata)) {
            $rules['cdata'] = 'required|string';
        } else {
            $rules['value'] = 'required|string';
        }
        return $rules;
    }
}
