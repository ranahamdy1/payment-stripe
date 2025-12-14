<?php

namespace App\Modules\Payments\Stripe1\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payments\Stripe1\Requests\InitiatePaymentRequest;
use App\Modules\Payments\Stripe1\Resources\PaymentResource;
use App\Modules\Payments\Stripe1\Services\OrderService;
use App\Modules\Payments\Stripe1\Services\PaymentService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponse;

    protected PaymentService $paymentService;
    protected OrderService $orderService;

    public function __construct(PaymentService $paymentService, OrderService $orderService)
    {
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
    }

    public function initiate(InitiatePaymentRequest $request): JsonResponse
    {
        $orderId = $request->validated()['order_id'] ?? null;

        if (!$orderId) {
            return $this->validationError(['order_id' => 'Order ID is required']);
        }

        $order = $this->orderService->getOrder($orderId);

        if (!$order) {
            return $this->notFound("Order with ID {$orderId} not found");
        }

        try {
            $payment = $this->paymentService->initiatePayment($order);
        } catch (\Exception $e) {
            return $this->error('Failed to initiate payment: ' . $e->getMessage(), 500);
        }

        return $this->success(
            new PaymentResource($payment),
            'Payment initiated successfully',
            200
        );
    }

    public function successPayment(Request $request)
    {
        $sessionId = $request->query('session_id');
        return response()->view('payments.success', [
            'session_id' => $sessionId
        ]);
    }

    public function cancelPayment(Request $request)
    {
        return response()->view('payments.cancel');
    }



}
