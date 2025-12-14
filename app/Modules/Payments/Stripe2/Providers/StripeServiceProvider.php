<?php

namespace App\Modules\Payments\Stripe2\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class StripeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::middleware('api')
            ->prefix(config('app.api_prefix', 'api/v1'))
            ->group(__DIR__ . '/../routes/api.php');
    }
}
