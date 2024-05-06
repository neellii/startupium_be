<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'firstname' => ['required', 'string', 'max:255', 'min:2'],
            'lastname' => [],
            'desiredPosition' => ['required', 'string', 'max:255', 'min:2'],
            'socials' => ['string'],
            'city' => [],
            'region' => [],
            'country' => [],
        ];
    }
}
