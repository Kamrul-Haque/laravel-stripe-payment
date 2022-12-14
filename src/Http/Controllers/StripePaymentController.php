<?php

namespace KamrulHaque\LaravelStripePayment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use KamrulHaque\LaravelStripePayment\Models\StripePayment;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripePaymentController extends Controller
{
    public function index()
    {
        $stripePayments = StripePayment::latest()->paginate(5);

        return view('laravel-stripe-payment::index', compact('stripePayments'));
    }

    public function store(Request $request)
    {
        $valid = $request->validate([
            'payment_intend_id' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'gt:0'],
        ]);

        $valid['date'] = today()->toDateString();
        $valid['user_id'] = auth()->user() ? auth()->user()->id : null;

        if (StripePayment::create($valid))
            return response()->json(['payment_intend_id' => $request->get('payment_intend_id')]);

        return response()->json(['error' => 'cant store payment'], 400);
    }

    public function create()
    {
        $amount = 10;
        $currency = 'GBP';

        try
        {
            Stripe::setApiKey(config('stripe.secret_key'));

            $stripePublicKey = config('stripe.public_key');

            $paymentIntend = PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => $currency,
                'payment_method_types' => ['card'],
            ]);

            $clientSecret = $paymentIntend->client_secret;
            $publicKey = $stripePublicKey;

            return view('laravel-stripe-payment::checkout', compact('clientSecret', 'publicKey', 'amount'));

        }
        catch (\Exception $exception)
        {
            return back()->with(['error' => $exception->getMessage()], 500);
        }
    }

    public function refund(Request $request, StripePayment $stripePayment)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'lte:' . ($stripePayment->amount - $stripePayment->refunded_amount)],
        ]);

        $amount = $request->input('amount');
        $stripeClient = new StripeClient(config('stripe.secret_key'));

        try
        {
            $refund = $stripeClient->refunds->create([
                'payment_intent' => $stripePayment->payment_intend_id,
                'amount' => $amount * 100
            ]);

            if ($refund->status === 'succeeded')
                $stripePayment->update([
                    'refunded_amount' => $stripePayment->refunded_amount + $amount,
                    'status' => 'Refunded',
                ]);
        }
        catch (\Exception $exception)
        {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Refund Initiated Successfully');
    }
}
