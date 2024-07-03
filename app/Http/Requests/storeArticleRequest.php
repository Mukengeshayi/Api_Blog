<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'required|string|max:100|min:10|unique:posts',
            'description'=>'required|string',
            'image'=>'required|image|max:2048',
            'views'=>'nullable|int',
            'category_id'=>'required',
        ];
    }
}
