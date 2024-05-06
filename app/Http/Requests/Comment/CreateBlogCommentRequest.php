<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CreateBlogCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string'],
            'slug' => ['required', 'string', 'max:500']
        ];
    }

    public function messages()
    {
        return [
            'slug.max' => config('constants.transmit_incorrect_data'),
            'slug.string' => config('constants.transmit_incorrect_data'),
            'slug.required' => config('constants.transmit_incorrect_data'),
            'message.string' => config('constants.transmit_incorrect_data'),
            'message.required' => config('constants.transmit_incorrect_data'),
        ];
    }
}
