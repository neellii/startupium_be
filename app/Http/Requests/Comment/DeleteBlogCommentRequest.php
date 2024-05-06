<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class DeleteBlogCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'commentId' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'commentId.required' => config('constants.transmit_incorrect_data'),
        ];
    }
}
