<?php

namespace Tests\Unit\Services\Payment;

use App\Services\Payment\Handlers\BraintreeHandler;
use App\Services\Payment\Handlers\PaymentHandler;
use App\Services\Payment\PaymentManager;
use InvalidArgumentException;
use Tests\TestCase;

class PaymentManagerTest extends TestCase
{
    /**
     * @var \App\Services\Payment\PaymentManager
     */
    private $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = app(PaymentManager::class);
    }

    public function testDriverMustBeSpecified()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("No payment driver was specified.");

        $this->manager->driver();
    }

    public function testCreatePrzelewy24Driver()
    {
        $driver = $this->manager->driver('braintree');

        $this->assertInstanceOf(PaymentHandler::class, $driver);
        $this->assertInstanceOf(BraintreeHandler::class, $driver);
    }
}
