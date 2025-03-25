<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BrandCreateOrUpdateRequest extends FormRequest
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
            'name'      => 'required',
            'is_active' => 'nullable',
        ];

        if ($this->id) {
            $rules['name'] .= '|unique:brands,name,' . $this->id;
        } else {
            $rules['name'] .= '|unique:brands,name,NULL,id';
        }

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
            'name.required' => 'The name field is required.',
        ];
    }
}
