<?php

namespace Tests\Unit\ValueObjects\Payment;

use App\ValueObjects\Payment as PaymentVO;
use Tests\TestCase;

class PaymentVOTest extends TestCase
{
    public function testId()
    {
        $vo = new PaymentVO('foo');

        $this->assertEquals('foo', $vo->id());
    }
}
