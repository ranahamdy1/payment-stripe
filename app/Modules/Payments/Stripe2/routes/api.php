<?php

use App\Modules\Payments\Stripe2\Controllers\StripeController;
use App\Modules\Payments\Stripe2\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('stripe')->group(function () {
    Route::post('/create-payment-intent', [StripeController::class, 'createPaymentIntent']);
    Route::post('/confirm-payment', [StripeController::class, 'confirmPayment']);
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
