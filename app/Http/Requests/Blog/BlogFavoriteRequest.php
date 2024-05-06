<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class BlogFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:500']
        ];
    }

    public function messages()
    {
        return [
            'slug.max' => config('constants.transmit_incorrect_data'),
            'slug.string' => config('constants.transmit_incorrect_data'),
            'slug.required' => config('constants.transmit_incorrect_data'),
        ];
    }
}
