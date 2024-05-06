<?php
namespace App\Http\Requests\Skill;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'skills' => 'array'
        ];
    }
}
