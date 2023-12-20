<?php

namespace KamrulHaque\LaravelStripePayment;

use Illuminate\Support\ServiceProvider;

class StripePaymentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/stripe.php', 'stripe');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/stripe.php' => config_path('stripe.php'),
            __DIR__ . '/../stubs/database/migrations/create_stripe_payments_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_stripe_payments_table.php'),
            __DIR__ . '/../stubs/Models/StripePayment.php' => app_path('Models/StripePayment.php'),
            __DIR__ . '/../stubs/Http/Controllers/StripePaymentController.php' => app_path('Http/Controllers/StripePaymentController.php'),
            __DIR__ . '/../resources/views' => resource_path('views/stripe-payments/'),
            __DIR__ . '/../public' => public_path('vendor/laravel-stripe-payment'),
            __DIR__ . '/../stubs/routes/stripe.php' => base_path('routes/stripe.php'),
        ], 'laravel-stripe');
    }
}
