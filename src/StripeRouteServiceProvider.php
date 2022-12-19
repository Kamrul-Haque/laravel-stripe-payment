<?php

namespace KamrulHaque\LaravelStripePayment;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class StripeRouteServiceProvider extends RouteServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/stripe.php'));
        });
    }
}
