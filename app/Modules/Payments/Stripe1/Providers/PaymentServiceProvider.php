<?php

namespace App\Modules\Payments\Stripe1\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::middleware('api')
            ->prefix(config('app.api_prefix', 'api/v1'))
            ->group(__DIR__ . '/../routes/api.php');
    }
}
