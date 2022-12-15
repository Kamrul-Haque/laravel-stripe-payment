# laravel-stripe-payment

Add Stripe payment functionality with partial refund feature to your existing Laravel project. The package uses the latest stripe.js v3 and fully customizable to your need.

## Installation

Install the package via [composer](https://getcomposer.org/):
```
composer require kamrul-haque/laravel-stripe-payment
```

Publish ``migrations`` needed for storing the payments:
```
php artisan vendor:publish --provider="KamrulHaque\LaravelStripePayment\StripePaymentServiceProvider" --tag="migrations"
```

Migrate the necessary database tables:
```
php artisan migrate
```

Publish ``checkout.js`` needed for ``Stripe`` checkout to your public folder:
```
php artisan vendor:publish --provider="KamrulHaque\LaravelStripePayment\StripePaymentServiceProvider" --tag="public"
```

## Configuration

Set the ``Stripe`` *Api Keys* in ``.env``:
```
// .env

STRIPE_PUBLIC_KEY=
STRIPE_SECRET_KEY=
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
use App\Http\Controllers as Controllers;

Route::group(['middleware' => 'custom'], function () {
    Route::resource('stripe-payments', Controllers\StripePaymentController::class)
         ->only('index', 'create', 'store');
    Route::post('stripe-payments/{stripePayment}/refund', [Controllers\StripePaymentController::class, 'refund'])
         ->name('stripe-payments.refund');
});

```
- Change *Namespaces* in ``StripePaymentController``