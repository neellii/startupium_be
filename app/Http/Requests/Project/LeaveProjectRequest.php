<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class LeaveProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];
        if (gettype(request()->subscriber) === 'integer') {
            $rules['subscriber'] = 'required|integer';
        } else {
            $rules['subscriber'] = 'required|string';
        }
        return $rules;
    }
}
