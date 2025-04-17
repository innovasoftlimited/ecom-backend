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
            'id'                                   => 'nullable|exists:products,id',
            'name'                                 => 'required|string|max:255',
            'description'                          => 'nullable|string',
            'category_id'                          => 'required|exists:categories,id',
            'brand_id'                             => 'required|exists:brands,id',
            'thumb_image'                          => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',

            'featured'                             => 'required|boolean',
            'best_seller'                          => 'required|boolean',
            'unit_price'                           => 'required|numeric|min:0',
            'is_active'                            => 'required|boolean',

            'product_details'                      => 'required|array|min:1',
            'product_details.*.id'                 => 'nullable|exists:product_details,id',
            'product_details.*.size_attribute_id'  => 'required|exists:product_attributes,id',
            'product_details.*.color_attribute_id' => 'required|exists:product_attributes,id',
            'product_details.*.sku'                => 'required|string|max:100',
            'product_details.*.unit_price'         => 'required|numeric|min:0',
            'product_details.*.quantity'           => 'required|integer|min:0',
            'product_details.*.image'              => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //     ];
    // }
}
