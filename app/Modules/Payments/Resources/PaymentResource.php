<?php

namespace App\Modules\Payments\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'payment_id' => $this->id,
            'order_id' => $this->order_id,
            'payment_gateway' => $this->payment_gateway,
            'transaction_id' => $this->transaction_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'checkout_url' => $this->metadata['checkout_url'] ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
