<?php

namespace App\Modules\Payments\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'customer_email' => $this->customer_email,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
