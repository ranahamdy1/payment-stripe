<?php

namespace App\Modules\Payments\Repositories;

use App\Models\Payment;

class PaymentRepository
{
    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function findByTransactionId(string $transactionId): ?Payment
    {
        return Payment::where('transaction_id', $transactionId)->first();
    }
}
