<?php declare(strict_types = 1);

namespace LifenPagTests\Units;

use PHPUnit\Framework\TestCase;

use LifenPag\Asaas\V3\Domains\Payment;

class PaymentTest extends TestCase
{
    public function testPaymentDomainCreate(): void
    {
        $payment = new Payment([
            'id' => 1,
            'customer' => 'Homer Simpson',
            'value' => 12,
        ]);

        $this->assertEquals('Homer Simpson', $payment->customer);
        $this->assertEquals(12, $payment->value);
        $this->assertEquals(1, $payment->id);
    }
}
