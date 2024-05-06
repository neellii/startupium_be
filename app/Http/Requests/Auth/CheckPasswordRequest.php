<?php
namespace App\Http\Requests\Auth;

use App\Rules\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class CheckPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => ['required', new MatchOldPassword()],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ];
    }
}
