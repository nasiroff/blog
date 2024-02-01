<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
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
            'category_id' => ['required', 'string', 'numeric', Rule::exists('categories', 'id')],
            'title' => ['required', 'string', 'min:3', 'max:140'],
            'content' => ['required', 'string', 'min:10', 'max:2000'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimetypes:image/jpeg,image/png', 'max:4096']
        ];
    }
}
