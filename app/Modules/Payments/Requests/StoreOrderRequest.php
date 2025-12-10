<?php

namespace App\Modules\Payments\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.5',
            'currency' => 'required|string|size:3',
            'customer_email' => 'nullable|email',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Amount is required',
            'currency.required' => 'Currency is required',
            'currency.size' => 'Currency must be 3 characters (e.g. USD)',
        ];
    }
}
