# laravel-stripe-payment

Add Stripe payment functionality with partial refund feature to your existing Laravel project. The package uses the latest stripe.js v3 and fully customizable to your need.

## Installation

Install the package via [composer](https://getcomposer.org/):
```
composer require kamrul-haque/laravel-stripe-payment
```

Migrate the necessary database tables:
```
php artisan migrate
```

Publish ``checkout.js`` needed for ``Stripe`` checkout to your public folder:
```
php artisan vendor:publish --provider="KamrulHaque\LaravelStripePayment\StripePaymentServiceProvider" --tag="public"
```

## Usage

- Access the checkout page by ``stripe-payments/create`` *uri* added to your application by the package
- Access the payments completed and partial refund functionality by ``stripe-payments`` *uri* added to your application by the package

## Cutomization

- publish package resources:
```
php artisan vendor:publish --provider="KamrulHaque\LaravelStripePayment\StripePaymentServiceProvider"
```
- By default package routes are protected by ``Auth Middleware``. Override package routes to customize that:
```
// 'routes/web.php'

<?php

use Illuminate\Support\Facades\Route;
use KamrulHaque\LaravelStripePayment\Http\Controllers as LaravelStripePayment;

Route::group(['middleware' => 'custom'], function () {
    Route::resource('stripe-payments', LaravelStripePayment\StripePaymentController::class)
         ->only('index', 'create', 'store');
    Route::post('stripe-payments/{stripePayment}/refund', [LaravelStripePayment\StripePaymentController::class, 'refund'])
         ->name('stripe-payments.refund');
});

```
