<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;

class RemoveRequireForTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [];
        if (gettype(request()->tagId) === 'integer') {
            $rules['tagId'] = 'required|integer';
        } else {
            $rules['tagId'] = 'required|string';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'tagId.required' => 'Поле tagId обязательно для заполнения.',
            'tagId.string' => 'Поле tagId должно быть строкой или числом',
            'tagId.integer' => 'Поле tagId должно быть строкой или числом',
        ];
    }
}
