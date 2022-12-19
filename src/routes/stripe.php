<?php

\Illuminate\Support\Facades\Route::group(['middleware' => 'web'], function () {
     Route::resource('stripe-payments', \KamrulHaque\LaravelStripePayment\Http\Controllers\StripePaymentController::class)
          ->only('index', 'create', 'store');
     Route::post('stripe-payments/{stripePayment}/refund', [\KamrulHaque\LaravelStripePayment\Http\Controllers\StripePaymentController::class, 'refund'])
          ->name('stripe-payments.refund');
});
