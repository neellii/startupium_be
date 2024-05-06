<?php
namespace App\Http\Requests\Email;

use Illuminate\Foundation\Http\FormRequest;

class ResendEmailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email'
        ];
    }
}
