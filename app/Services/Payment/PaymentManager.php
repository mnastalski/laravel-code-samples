<?php

namespace App\Services\Payment;

use App\Services\Payment\Handlers\BraintreeHandler;
use App\Services\Payment\Handlers\PaymentHandler;
use Illuminate\Support\Manager;
use InvalidArgumentException;

/**
 * @method PaymentHandler driver(string|null $driver)
 */
class PaymentManager extends Manager
{
    /**
     * @return void
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException("No payment driver was specified.");
    }

    /**
     * Create a "Braintree" handler instance.
     *
     * @return PaymentHandler
     */
    protected function createBraintreeDriver(): PaymentHandler
    {
        return new BraintreeHandler(
            $this->app['config']['services.braintree']
        );
    }
}
