<?php
namespace App\Http\Requests\Auth;

use App\Rules\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'currentPassword' => ['required', new MatchOldPassword()],
            'newPassword' => 'required',
            'confirmPassword' => ['same:newPassword']
        ];
    }
}
