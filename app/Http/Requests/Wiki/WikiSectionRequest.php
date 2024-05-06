<?php

namespace App\Http\Requests\Wiki;

use Illuminate\Foundation\Http\FormRequest;

class WikiSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
        ];
    }
}
