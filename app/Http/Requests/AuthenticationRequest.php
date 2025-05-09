<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email:rfc,dns',
            'password' => 'required',
        ];
    }

    /**
     * messages
     *
     * @return array<string
     */
    public function messages()
    {
        return [
            'email.required'    => 'The email field is required.',
            'email.email'       => 'Please enter a valid email address.',
            'password.required' => 'The password field is required.',
            'email.dns'         => 'The domain does not have a valid DNS record.',
        ];
    }
}
