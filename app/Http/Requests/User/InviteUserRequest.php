<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class InviteUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'userId' => 'required',
            'projectId' => 'required'
        ];
    }
}
