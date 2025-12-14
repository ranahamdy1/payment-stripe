<?php

use App\Modules\Payments\Stripe2\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::prefix('stripe')->group(function () {
    Route::post('/create-payment-intent', [StripeController::class, 'createPaymentIntent']);
    Route::post('/confirm-payment', [StripeController::class, 'confirmPayment']);
});
