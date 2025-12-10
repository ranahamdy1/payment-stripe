<?php

namespace App\Modules\Payments\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Route::middleware('api')
            ->prefix(config('app.api_prefix', 'api/v1'))
            ->group(__DIR__ . '/../routes/api.php');
    }
}
