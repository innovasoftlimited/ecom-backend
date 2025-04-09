<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductCreateOrUpdateRequest extends FormRequest
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

        return [
            'name'                                 => 'required',
            'description'                          => 'nullable',
            'category_id'                          => 'required',
            'brand_id'                             => 'required',
            'thumb_image'                          => 'nullable',
            'featured'                             => 'required',
            'best_seller'                          => 'required',
            'unit_price'                           => 'required',
            'is_active'                            => 'required',
            'product_details'                      => 'required|array',
            'product_details.*.size_attribute_id'  => 'required',
            'product_details.*.color_attribute_id' => 'required',
            'product_details.*.sku'                => 'required',
            'product_details.*.unit_price'         => 'required',
            'product_details.*.quantity'           => 'required',
            'product_details.*.image'              => 'nullable',
        ];

    }

    // public function messages(): array
    // {
    //     return [
    //     ];
    // }
}
