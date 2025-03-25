<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductAttributeCreateOrUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'type'      => 'required|string',
            'value'     => 'required|string',
            'is_active' => 'nullable',
        ];

        return $rules;

    }

    /**
     * messages
     *
     * @return array<string
     */
    public function messages()
    {
        return [
            'type.required'  => 'The type field is required.',
            'value.required' => 'The value field is required.',
        ];
    }
}
