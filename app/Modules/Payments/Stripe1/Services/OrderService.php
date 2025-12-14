<?php

namespace App\Modules\Payments\Stripe1\Services;

use App\Models\Order;
use App\Modules\Payments\Stripe1\Repositories\OrderRepository;

class OrderService
{
    protected OrderRepository $orderRepo;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function createOrder(array $data): Order
    {
        $data['currency'] = strtolower($data['currency']);
        $data['status'] = $data['status'] ?? Order::STATUS_PENDING ?? 'pending';

        return $this->orderRepo->create($data);
    }

    public function getOrder(int $id): ?Order
    {
        return $this->orderRepo->findById($id);
    }
}
