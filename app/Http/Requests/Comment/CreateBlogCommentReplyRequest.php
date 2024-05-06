<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CreateBlogCommentReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string'],
            'commentId' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'commentId.required' => config('constants.transmit_incorrect_data'),
            'message.string' => config('constants.transmit_incorrect_data'),
            'message.required' => config('constants.transmit_incorrect_data'),
        ];
    }
}
