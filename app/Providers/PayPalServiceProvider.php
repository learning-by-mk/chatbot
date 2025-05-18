<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('paypal.client', function ($app) {
            $config = config('paypal');
            $mode = $config['mode'];

            // Khởi tạo PayPal client với API credentials
            $client = null;

            if ($mode === 'sandbox') {
                $client_id = $config['sandbox']['client_id'];
                $client_secret = $config['sandbox']['client_secret'];

                $environment = new \PayPal\Core\SandboxEnvironment($client_id, $client_secret);
            } else {
                $client_id = $config['live']['client_id'];
                $client_secret = $config['live']['client_secret'];

                $environment = new \PayPal\Core\ProductionEnvironment($client_id, $client_secret);
            }

            $client = new \PayPal\PayPalClient($environment);

            return $client;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../../config/paypal.php' => config_path('paypal.php'),
        ], 'config');
    }
}
