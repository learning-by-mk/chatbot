<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class PaypalConfigController extends Controller
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client();
    }
    public function getAccessToken()
    {
        try {
            $data = [
                "grant_type" => "client_credentials"
            ];
            $client_id = env('PAYPAL_SANDBOX_CLIENT_ID', "");
            $client_secret  = env('PAYPAL_SANDBOX_CLIENT_SECRET', "");
            $credentials = base64_encode("$client_id:$client_secret");

            $res = $this->client->post("https://api-m.sandbox.paypal.com/v1/oauth2/token", [
                'form_params' => $data,
                'headers' => [
                    "Content-Type" => "application/json",
                    "Authorization" => "Basic $credentials"
                ],
            ]);
            $dt = json_decode($res->getBody());
            return $dt->access_token;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function createOrder(Request $request)
    {
        try {
            if (isset($request->amount)) {
                $token = $this->getAccessToken();
                $body = [
                    "intent" => "CAPTURE",
                    "purchase_units" => [
                        [
                            "amount" => [
                                "currency_code" => $request->currency ?: "USD",
                                "value" => $request->amount
                            ],
                            "customer_id" => Auth::user()->id,
                            "description" => $request->description ?: "ManhKhanh DT"
                        ]
                    ],
                    "application_context" => [
                        "user_action" => "PAY_NOW",
                        "return_url" => route('paypal.config.success'),
                        "cancel_url" => route('paypal.config.cancel'),
                    ]
                ];

                $uri = "https://api-m.sandbox.paypal.com/v2/checkout/orders";
                $res = $this->client->post($uri, [
                    "json" => $body,
                    "headers" => [
                        "Content-Type" => "application/json",
                        "Authorization" => "Bearer $token"
                    ]
                ]);
                $orderDetail = json_decode($res->getBody());
                foreach ($orderDetail->links as $link) {
                    if ($link->rel == "approve") return redirect()->away($link->href);
                }
            }
        } catch (Exception $e) {
            return $e;
        }
    }

    public function capturePaymentOrder(string $orderId)
    {
        try {
            $token = $this->getAccessToken();

            // $body = [];
            $uri = "https://api-m.sandbox.paypal.com/v2/checkout/orders/$orderId/capture";

            $res = $this->client->post($uri, [
                "headers" => [
                    "Authorization" => "Bearer $token",
                    "Content-Type" => "application/json"
                ]
            ]);

            return json_decode($res->getBody());
        } catch (Exception $e) {
            return $e;
        }
    }

    public function success(Request $request)
    {
        return $this->capturePaymentOrder($request->token);
    }

    public function cancel()
    {
        return "canceled";
    }
}
