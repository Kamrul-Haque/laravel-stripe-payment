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
        // for configuration files
        $this->publishes([
            __DIR__ . '/../config/stripe.php' => config_path('stripe.php'),
        ], 'configs');

        // for routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // for migrations
        if (!class_exists('CreateStripePaymentsTable'))
        {
            $this->publishes([
                __DIR__ . '/../stubs/create_stripe_payments_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_stripe_payments_table.php')
            ], 'migrations');
        }

        // for views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'laravel-stripe-payment');
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/laravel-stripe-payment/'),
        ], 'views');

        // for public assets
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/laravel-stripe-payment'),
        ], 'public');


        // for backend
        $this->publishes([
            __DIR__ . '/Models' => app_path('Models/'),
            __DIR__ . '/Http/Controllers' => app_path('Http/Controllers/')
        ], 'app');
    }
}
