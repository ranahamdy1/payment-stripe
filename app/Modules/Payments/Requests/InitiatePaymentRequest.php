<?php

namespace App\Modules\Payments\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|integer|exists:orders,id',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Order ID is required',
            'order_id.exists'   => 'The selected order does not exist',
        ];
    }
}
