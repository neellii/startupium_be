<?php
namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:500|min:3',
            'about' => 'required|string',
            'description' => 'required|string|max:200',
            'slug' => ['string', 'max:500', 'unique:projects,slug', 'required'],
        ];
    }

    protected function prepareForValidation() :void
    {
        $this->merge([
            'slug' => generateSlug($this->title, 500)
        ]);

    }

    public function messages()
    {
        return [
            'slug.unique' => config('constants.project_title_exists'),
            'title.min' => 'Количество символов в названии проекта должно быть не менее 3.',
            'title.max' => 'Количество символов в названии проекта не может превышать 500.',
            'slug.max' => 'Количество символов в названии проекта не может превышать 500.',
        ];
    }
}
