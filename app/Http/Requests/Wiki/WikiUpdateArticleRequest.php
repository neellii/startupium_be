<?php

namespace App\Http\Requests\Wiki;

use Illuminate\Foundation\Http\FormRequest;

class WikiUpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'articleId' => 'required',
            'title' => 'required|string',
            'text' => 'required|string',
            'sectionId' => '', // для изменения положения
            'hasDefault' => ''
        ];
    }
}
