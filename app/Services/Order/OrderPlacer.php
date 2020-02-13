<?php

namespace App\Services\Order;

use App\Constants\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\Payment\Exceptions\PaymentException;
use App\Services\Payment\PaymentManager;
use App\Services\Product\ProductFetcher;

class OrderPlacer
{
    /**
     * @var \App\Services\Product\ProductFetcher
     */
    private $productFetcher;

    /**
     * @var \App\Services\Order\OrderCreator
     */
    private $orderCreator;

    /**
     * @var \App\Services\Order\OrderUpdater
     */
    private $orderUpdater;

    /**
     * @var \App\Services\Payment\PaymentManager
     */
    private $paymentManager;

    /**
     * @param \App\Services\Product\ProductFetcher $productFetcher
     * @param \App\Services\Order\OrderCreator $orderCreator
     * @param \App\Services\Order\OrderUpdater $orderUpdater
     * @param \App\Services\Payment\PaymentManager $paymentManager
     */
    public function __construct(
        ProductFetcher $productFetcher,
        OrderCreator $orderCreator,
        OrderUpdater $orderUpdater,
        PaymentManager $paymentManager
    ) {
        $this->productFetcher = $productFetcher;
        $this->orderCreator = $orderCreator;
        $this->orderUpdater = $orderUpdater;
        $this->paymentManager = $paymentManager;
    }

    /**
     * @param \App\Models\User|null $user
     * @param array $data
     * @return \App\Models\Order
     * @throws \Exception
     */
    public function place(?User $user, array $data): Order
    {
        $paymentMethod = $data['payment_method'];

        $paymentDriver = $this->paymentManager->driver($paymentMethod);

        // Fetch purchased item
         $product = $this->productFetcher->find($data['product_uuid']);

        // Create an order with status "created"
         $order = $this->orderCreator->create($data);

        try {
            $payment = $paymentDriver->create([
                'amount' => $product->id,
                'fname' => $data['fname'],
                'lname' => $data['fname'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'token' => $data['payment_token'],
            ]);
        } catch (PaymentException $e) {
            $this->orderUpdater->paymentStatus($order, $user, PaymentStatus::FAILED);

            throw $e;
        }

        // Update status to pending
        $this->orderUpdater->paymentStatus($order, $user, PaymentStatus::COMPLETED);

        // Update payment token
        $this->orderUpdater->paymentToken($order, $payment->token());

        return $order;
    }
}
