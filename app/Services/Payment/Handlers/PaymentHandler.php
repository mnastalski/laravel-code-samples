<?php

namespace App\Services\Payment\Handlers;

use App\ValueObjects\Payment as PaymentVO;

interface PaymentHandler
{
    /**
     * Create a payment.
     *
     * @param array $parameters
     * @return PaymentVO
     */
    public function create(array $parameters): PaymentVO;
}
