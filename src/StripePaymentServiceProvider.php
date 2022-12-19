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
        $this->app->register('KamrulHaque\LaravelStripePayment\StripeRouteServiceProvider');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // for routes
        $this->loadRoutesFrom(__DIR__ . '/routes/stripe.php');

        // for configuration files
        $this->publishes([
            __DIR__ . '/../config/stripe.php' => config_path('stripe.php'),
        ], 'laravel-stripe-configs');

        // for migrations
        if (!class_exists('CreateStripePaymentsTable')) {
            $this->publishes([
                __DIR__ . '/../stubs/database/migrations/create_stripe_payments_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_stripe_payments_table.php')
            ], 'laravel-stripe-migrations');
        }

        // for views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'laravel-stripe-payment');
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/laravel-stripe-payment/'),
        ], 'laravel-stripe-views');

        // for public assets
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/laravel-stripe-payment'),
        ], 'laravel-stripe-public');


        // for backend
        $this->publishes([
            __DIR__ . '/../stubs/Models/StripePayment.php.stub' => app_path('Models/StripePayment.php'),
            __DIR__ . '/../Stubs/Http/Controllers/StripePaymentController.php.stub' => app_path('Http/Controllers/StripePaymentController.php'),
            __DIR__ . '/../stubs/routes/stripe.php.stub' => base_path('routes/stripe.php')
        ], 'laravel-stripe');
    }
}
