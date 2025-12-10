<?php

namespace App\Modules\Payments\Services;

use App\Modules\Payments\Repositories\PaymentRepository;
use App\Models\Payment;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PaymentService
{
    protected PaymentRepository $paymentRepo;

    public function __construct(PaymentRepository $paymentRepo)
    {
        $this->paymentRepo = $paymentRepo;
    }

    public function initiatePayment(Order $order): Payment
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));

        $amountInCents = (int) round($order->amount * 100);

        $successUrl = config('app.url') . '/payment/success?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl  = config('app.url') . '/payment/cancel';

        $session = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => $order->currency,
                    'product_data' => [
                        'name' => 'Order #' . $order->id,
                    ],
                    'unit_amount' => $amountInCents,
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'order_id' => $order->id,
            ],
            'customer_email' => $order->customer_email,
            'success_url' => $successUrl,
            'cancel_url'  => $cancelUrl,
        ]);

        return $this->paymentRepo->create([
            'order_id' => $order->id,
            'payment_gateway' => 'stripe',
            'transaction_id' => $session->id,
            'amount' => $order->amount,
            'status' => 'pending',
            'metadata' => [
                'checkout_url' => $session->url ?? null,
            ],
        ]);
    }
}
