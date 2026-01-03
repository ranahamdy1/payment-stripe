<?php

namespace App\Modules\Payments\Stripe2\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    use ApiResponse;

    /**
     * Create a Stripe PaymentIntent
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        try {
            $order = Order::findOrFail($request->order_id);

            Stripe::setApiKey(config('services.stripe.secret_key'));

            // minimum amount logic
            $amount = max((float)$order->amount, 0.5);

            $paymentIntent = PaymentIntent::create([
                'amount' => (int) round($amount * 100),
                'currency' => $order->currency,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => 'stripe',
                'amount' => $order->amount,
                'status' => 'pending',
                'stripe_payment_intent_id' => $paymentIntent->id,
            ]);

            return $this->success([
                'client_secret' => $paymentIntent->client_secret,
                'payment_id' => $payment->id,
                'publishableKey' => config('services.stripe.public_key'),
            ], 'PaymentIntent created successfully');

        } catch (\Exception $e) {
            Log::error('Stripe createPaymentIntent error: ' . $e->getMessage());
            return $this->error('Failed to create PaymentIntent: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Confirm a Stripe payment
     */
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        $payment = Payment::where('order_id', $order->id)->firstOrFail();

        if ($payment->status === 'paid') {
            return $this->error('Payment already completed', 400);
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret_key'));

            $intent = PaymentIntent::retrieve($payment->stripe_payment_intent_id);
            $intent->confirm(['payment_method' => $request->payment_method_id]);

            if ($intent->status === 'succeeded') {
                $payment->update(['status' => 'paid']);
                $order->update(['status' => 'paid', 'payment_id' => $payment->id]);
            }

            return $this->success([
                'stripe_status' => $intent->status,
            ], 'Payment confirmation status');

        } catch (\Exception $e) {
            Log::error('Stripe confirmPayment error: ' . $e->getMessage());
            return $this->error('Failed to confirm payment: ' . $e->getMessage(), 400);
        }
    }
}
