<?php

namespace KamrulHaque\LaravelStripePayment;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class StripeRouteServiceProvider extends RouteServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapStripeRoutes();
    }

    protected function mapStripeRoutes()
    {
        Route::middleware(['web', 'auth'])
             ->group(base_path('routes/stripe.php'));
    }
}