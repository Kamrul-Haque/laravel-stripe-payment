# laravel-stripe-payment

[![Latest Stable Version](http://poser.pugx.org/kamrul-haque/laravel-stripe-payment/v)](https://packagist.org/packages/kamrul-haque/laravel-stripe-payment) [![Total Downloads](http://poser.pugx.org/kamrul-haque/laravel-stripe-payment/downloads)](https://packagist.org/packages/kamrul-haque/laravel-stripe-payment) [![Latest Unstable Version](http://poser.pugx.org/kamrul-haque/laravel-stripe-payment/v/unstable)](https://packagist.org/packages/kamrul-haque/laravel-stripe-payment) [![License](http://poser.pugx.org/kamrul-haque/laravel-stripe-payment/license)](https://packagist.org/packages/kamrul-haque/laravel-stripe-payment) ![GitHub Repo stars](https://img.shields.io/github/stars/Kamrul-Haque/laravel-stripe-payment?color=F5BD16)

Add Stripe payment functionality with partial refund feature to your existing Laravel project. The package uses the latest stripe.js v3 and fully customizable to your need.

## Installation

Install the package via [composer](https://getcomposer.org/):
```
composer require kamrul-haque/laravel-stripe-payment
```

Publish ``resources`` of the package:
```
php artisan vendor:publish --tag="laravel-stripe"
```

Migrate the necessary database tables:
```
php artisan migrate
```

## Configuration

Set the ``Stripe`` *Api Keys* in ``.env``:
```
// .env

STRIPE_PUBLIC_KEY=
STRIPE_SECRET_KEY=
```

Add ``routes`` to ``web.php``:
```
// routes/web.php

<?php

use App\Http\Controllers as Controllers;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    require __DIR__.'/stripe.php';
});
```

## Usage

- Access the checkout page by ``stripe-payments/create`` *uri* added to your application by the package
- Access the payments completed and partial refund functionality by ``stripe-payments`` *uri* added to your application by the package
