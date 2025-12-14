<?php

use App\Modules\Payments\Controllers\PaymentController;
use App\Modules\Payments\Controllers\OrderController;
use App\Modules\Payments\Controllers\StripeWebhookController;

Route::post('/orders', [OrderController::class, 'store']);
Route::post('/payments/initiate', [PaymentController::class, 'initiate']);
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
