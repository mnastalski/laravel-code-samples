<?php

namespace App\Services\Payment\Handlers;

use App\Services\Payment\Exceptions\PaymentException;
use App\ValueObjects\Payment as PaymentVO;
use Braintree_Gateway;

class BraintreeHandler implements PaymentHandler
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var Braintree_Gateway
     */
    private $gateway;

    /**
     * @param  array  $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     * @throws PaymentException
     */
    public function create(array $parameters) : PaymentVO
    {
        $sale = $this->gateway()->transaction()->sale([
            'amount' => $parameters['amount'],
            'paymentMethodNonce' => $parameters['token'],
            'billing' => [
                'firstName' => $parameters['fname'],
                'lastName' => $parameters['lname'],
            ],
            'customer' => [
                'firstName' => $parameters['fname'],
                'lastName' => $parameters['lname'],
                'email' => $parameters['email'],
                'phone' => $parameters['phone_number'],
            ],
            'options' => [
                'submitForSettlement' => true,
            ],
        ]);

        if (!$sale->success) {
            throw new PaymentException($sale->message);
        }

        return new PaymentVO($sale->transaction->id);
    }

    /**
     * @return Braintree_Gateway
     */
    private function gateway() : Braintree_Gateway
    {
        if ($this->gateway) {
            return $this->gateway;
        }

        $this->gateway = new Braintree_Gateway([
            'environment' => $this->config['env'],
            'merchantId' => $this->config['merchant_id'],
            'publicKey' => $this->config['public_key'],
            'privateKey' => $this->config['private_key'],
        ]);

        return $this->gateway;
    }
}
