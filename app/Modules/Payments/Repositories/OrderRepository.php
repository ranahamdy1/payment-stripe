<?php

namespace App\Modules\Payments\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function findById(int $id): ?Order
    {
        return Order::find($id);
    }
}
