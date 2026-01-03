<?php

namespace App\Modules\Payments\Stripe2\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Traits\ApiResponse;

class StripeWebhookController extends Controller
{
    use ApiResponse;

    public function handle(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid webhook'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $this->handlePaymentSucceeded($event->data->object);
        }

        if ($event->type === 'payment_intent.payment_failed') {
            $this->handlePaymentFailed($event->data->object);
        }

        return response()->json(['status' => 'ok']);
    }

    protected function handlePaymentSucceeded($intent)
    {
        DB::transaction(function () use ($intent) {
            $payment = Payment::where('stripe_payment_intent_id', $intent->id)
                ->lockForUpdate()
                ->first();

            if (!$payment || $payment->status === 'paid') {
                return;
            }

            $payment->update(['status' => 'paid']);

            Order::where('id', $payment->order_id)->update([
                'status' => 'paid',
                'payment_id' => $payment->id,
            ]);
        });
    }

    protected function handlePaymentFailed($intent)
    {
        $payment = Payment::where('stripe_payment_intent_id', $intent->id)->first();

        if ($payment) {
            $payment->update(['status' => 'failed']);
        }
    }

}
