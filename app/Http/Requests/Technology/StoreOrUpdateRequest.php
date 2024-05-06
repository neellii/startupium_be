<?php
namespace App\Http\Requests\Technology;

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
            'technologies' => 'array'
        ];
    }
}
