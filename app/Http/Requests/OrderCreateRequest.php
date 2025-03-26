<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrderCreateRequest extends FormRequest
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
            'invoice_no'                         => 'required|string|unique:orders,invoice_no',
            'user_id'                            => 'required|exists:users,id',
            'total_price'                        => 'required|numeric|min:0',
            'status'                             => 'required|integer',
            'order_details'                      => 'required|array',
            'order_details.*.product_details_id' => 'required|exists:product_details,id',
            'order_details.*.quantity'           => 'required|integer|min:1',
            'order_details.*.total_price'        => 'required|numeric|min:0',
        ];

    }

}
