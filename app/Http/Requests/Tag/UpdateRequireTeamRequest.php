<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequireTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|min:3'
        ];
        if (gettype(request()->id) === 'integer') {
            $rules['id'] = 'required|integer';
        } else {
            $rules['id'] = 'required|string';
        }
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
            'id:required' => 'Поле id обязательно для заполнения.',
            'id:string' => 'Поле id должно быть строкой или числом.',
            'id:integer' => 'Поле id должно быть строкой или числом.',
            'title.string' => 'Поле специальность должно быть строкой',
            'title.required' => 'Поле специальность обязательно для заполнения.',
            'title.min' => 'Поле специальность должно состоять минимум из 3 символов.',
            'tagId:required' => 'Поле position обязательно для заполнения.',
            'tagId:string' => 'Поле position должно быть строкой или числом.',
            'tagId:integer' => 'Поле position должно быть строкой или числом.',
        ];
    }
}
