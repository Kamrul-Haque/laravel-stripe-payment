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

## Usage

- Access the checkout page by ``stripe-payments/create`` *uri* added to your application by the package
- Access the payments completed and partial refund functionality by ``stripe-payments`` *uri* added to your application by the package

## Cutomization

- publish package resources:
```
php artisan vendor:publish --provider="KamrulHaque\LaravelModelLog\ModelLogServiceProvider"
```
