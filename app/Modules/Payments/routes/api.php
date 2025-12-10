<?php

use App\Modules\Payments\Controllers\PaymentController;
use App\Modules\Payments\Controllers\OrderController;

Route::post('/orders', [OrderController::class, 'store']);
Route::post('/payments/initiate', [PaymentController::class, 'initiate']);
