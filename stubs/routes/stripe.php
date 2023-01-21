<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripePaymentController;

Route::resource('stripe-payments', StripePaymentController::class)
     ->only('index', 'create', 'store');
Route::post('stripe-payments/{stripePayment}/refund', [StripePaymentController::class, 'refund'])
     ->name('stripe-payments.refund');