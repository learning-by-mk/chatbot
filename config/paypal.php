<?php

/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'),
    'sandbox' => [
        'client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
        'app_id'        => env('PAYPAL_SANDBOX_APP_ID', ''),
    ],
    'live' => [
        'client_id'     => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
        'app_id'        => env('PAYPAL_LIVE_APP_ID', ''),
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Có thể là 'Sale', 'Authorization' hoặc 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // URL cho IPN (Instant Payment Notification)
    'locale'         => env('PAYPAL_LOCALE', 'en_US'), // Ví dụ: 'vi_VN' cho tiếng Việt, 'en_US' cho tiếng Anh
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true),

    // Cấu hình webhook của PayPal
    'webhook_id'     => env('PAYPAL_WEBHOOK_ID', ''),
];
