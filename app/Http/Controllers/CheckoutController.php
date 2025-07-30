<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Plan;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function stripe(Request $request)
    {
        $plan = Plan::findOrFail($request->plan_id);
        Stripe::setApiKey(config('stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $plan->currency,
                    'unit_amount' => $plan->price * 100,
                    'product_data' => ['name' => $plan->name],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => config('app.url') . '/success',
            'cancel_url' => config('app.url') . '/cancel',
        ]);

        return response()->json(['url' => $session->url]);
    }

    public function paypal(Request $request)
    {
        $plan = Plan::findOrFail($request->plan_id);
        $client = new Client();
        $accessToken = $this->getPaypalAccessToken($client);

        $response = $client->post('https://api-m.sandbox.paypal.com/v2/checkout/orders', [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => $plan->currency,
                        'value' => $plan->price,
                    ]
                ]],
                'application_context' => [
                    'return_url' => config('app.url') . '/paypal-success',
                    'cancel_url' => config('app.url') . '/paypal-cancel',
                ]
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $url = collect($data['links'])->firstWhere('rel', 'approve')['href'];

        return response()->json(['url' => $url]);
    }

    private function getPaypalAccessToken($client)
    {
        $response = $client->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
            'auth' => [config('paypal.client_id'), config('paypal.secret')],
            'form_params' => ['grant_type' => 'client_credentials'],
        ]);

        return json_decode($response->getBody(), true)['access_token'];
    }
}

