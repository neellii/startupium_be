<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class PostProfileRequest extends FormRequest
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
            'skills' => ['array'],
            'qualities' => ['array'],
            'socials' => ['string'],
            'carrers' => ['string'],
            'city' => '',
            'region' => '',
            'country' => ''
        ];
    }
}
