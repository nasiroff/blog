<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'username' => ['required', 'min:5', 'max:20', 'string', Rule::unique(User::class, 'username')],
            'name'     => ['required', 'min:3', 'max:20', 'string', 'alpha'],
            'email'    => ['required', 'string', 'email', Rule::unique(User::class, 'email')],
            'password' => ['required_with:password_confirmation', 'string', 'min:3', 'max:50', 'confirmed']
        ];
    }
}
