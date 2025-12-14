<?php

use App\Modules\Payments\Stripe1\Controllers\OrderController;
use App\Modules\Payments\Stripe1\Controllers\PaymentController;
use App\Modules\Payments\Stripe1\Controllers\StripeWebhookController;

Route::post('/orders', [OrderController::class, 'store']);
Route::post('/payments/initiate', [PaymentController::class, 'initiate']);
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

Route::get('/payment/success', [PaymentController::class, 'successPayment']);
Route::get('/payment/cancel', [PaymentController::class, 'cancelPayment']);
