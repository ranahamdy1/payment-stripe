<?php

return [
    App\Providers\AppServiceProvider::class,
    \App\Modules\Payments\Stripe1\Providers\PaymentServiceProvider::class,
    \App\Modules\Payments\Stripe2\Providers\StripeServiceProvider::class,
];
