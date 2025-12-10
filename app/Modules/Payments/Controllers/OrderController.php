<?php

namespace App\Modules\Payments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payments\Requests\StoreOrderRequest;
use App\Modules\Payments\Resources\OrderResource;
use App\Modules\Payments\Services\OrderService;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

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
