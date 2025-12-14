<?php

namespace App\Modules\Payments\Stripe2\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Traits\ApiResponse;

class StripeController extends Controller
{
    use ApiResponse;

    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        try {
            $order = Order::findOrFail($request->order_id);

            Stripe::setApiKey(config('services.stripe.secret_key'));

            $amount = intval(round($order->amount * 100));

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => $order->currency,
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => 'stripe',
                'transaction_id' => $paymentIntent->id,
                'amount' => $order->amount,
                'status' => 'pending',
                'metadata' => [
                    'client_secret' => $paymentIntent->client_secret,
                ],
            ]);

            return $this->success([
                'payment_intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'payment_id' => $payment->id,
                'publishableKey' => config('services.stripe.public_key'),
            ], 'Payment intent created successfully');

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        try {
            $order = Order::findOrFail($request->order_id);

            Stripe::setApiKey(config('services.stripe.secret_key'));

            $intent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($intent->status !== 'succeeded') {
                return $this->error('Payment not completed', 400, ['stripe_status' => $intent->status]);
            }

            $payment = Payment::where('transaction_id', $intent->id)->where('order_id', $order->id)->firstOrFail();

            $payment->update(['status' => 'paid']);
            $order->update(['status' => 'paid', 'payment_id' => $payment->id]);

            return $this->success(['payment_id' => $payment->id], 'Payment confirmed & order updated successfully');

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
