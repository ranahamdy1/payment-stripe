<?php

namespace App\Modules\Payments\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payments\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Traits\ApiResponse;

class StripeWebhookController extends Controller
{
    use ApiResponse;

    protected PaymentRepository $paymentRepo;

    public function __construct(PaymentRepository $paymentRepo)
    {
        $this->paymentRepo = $paymentRepo;
    }

    public function handle(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            // الوضع التجريبي على Postman
            $event = json_decode($payload, true);

            // الوضع الحقيقي بعد الاختبار
            // $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            switch ($event['type'] ?? '') {
                case 'checkout.session.completed':
                    $session = $event['data']['object'];

                    $payment = $this->paymentRepo->findByTransactionId($session['id'] ?? null);

                    if (!$payment) {
                        return $this->notFound('Payment not found');
                    }

                    // تحديث حالة الدفع
                    $payment->update([
                        'status' => 'paid',
                        'metadata' => array_merge(
                            $payment->metadata ?? [],
                            ['stripe_payment_intent' => $session['payment_intent'] ?? null]
                        ),
                    ]);

                    // تحديث حالة الـ Order
                    $order = $payment->order;
                    if ($order) {
                        $order->update([
                            'status' => 'paid',
                            'payment_id' => $payment->id,
                        ]);
                    }
                    break;

                case 'checkout.session.expired':
                    $session = $event['data']['object'];
                    $payment = $this->paymentRepo->findByTransactionId($session['id'] ?? null);

                    if (!$payment) {
                        return $this->notFound('Payment not found');
                    }

                    $payment->update(['status' => 'expired']);
                    break;
            }

            return $this->success(null, 'Webhook handled successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }
}
