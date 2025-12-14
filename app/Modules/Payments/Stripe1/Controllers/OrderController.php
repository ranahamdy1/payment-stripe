<?php

namespace App\Modules\Payments\Stripe1\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payments\Stripe1\Requests\StoreOrderRequest;
use App\Modules\Payments\Stripe1\Resources\OrderResource;
use App\Modules\Payments\Stripe1\Services\OrderService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    use ApiResponse;

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder($request->validated());

        return $this->success(
            new OrderResource($order),
            'Order created successfully',
            201
        );
    }
}
