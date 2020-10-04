<?php declare(strict_types = 1);

namespace LifenPagTests\Units;

use PHPUnit\Framework\TestCase;

use DateTime;

use LifenPag\Asaas\V3\{
    Client,
    Domains\Customer as CustomerDomain,
    Domains\Payment as PaymentDomain,
    Entities\Customer as CustomerEntity,
    Entities\Payment as PaymentEntity,
    Collections\Payment as PaymentCollection,
};

class PaymentTest extends TestCase
{
    public $apiKey = 'c0f2a7a68ec715cd5164fa729d0225a1645b1027cbc12f9ca7c83dead43952d1';
    public $environment = 'sandbox';

    public function testPaymentDomainCreate(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $paymentDomain = new PaymentDomain();
        $this->assertInstanceOf(PaymentDomain::class, $paymentDomain);
    }

    public function testPaymentEntityCreate(): void
    {
        $paymentEntity = new PaymentEntity();

        $this->assertInstanceOf(PaymentEntity::class, $paymentEntity);
    }

    public function testCreatePaymentByEntity(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $payment = new PaymentEntity();
        $payment->customer = 'cus_000003281443';
        $payment->billingType = 'BOLETO';
        $payment->value = 100;
        $payment->dueDate = (new DateTime())->format('Y-m-d');
        $paymentCreated = $payment->create();

        $this->assertInstanceOf(PaymentEntity::class, $paymentCreated);
        $this->assertNotNull($paymentCreated->id);
        $this->assertEquals('BOLETO', $paymentCreated->billingType);
        $this->assertEquals(100, $paymentCreated->value);
    }

    public function testCreatePaymentByCustomerDomain(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customer = new CustomerEntity();
        $customer->name = 'Homer Simpson';
        $customer->email = 'homer.simpson@lifenpag.com';
        $customer->cpfCnpj = '85267610054';

        $payment = new PaymentEntity();
        $payment->billingType = 'BOLETO';
        $payment->value = 100;
        $payment->dueDate = (new DateTime())->format('Y-m-d');

        $paymentCreated = CustomerDomain::create($customer)->doPayment($payment);

        $this->assertInstanceOf(PaymentEntity::class, $paymentCreated);
        $this->assertNotNull($paymentCreated->id);
        $this->assertEquals('BOLETO', $paymentCreated->billingType);
        $this->assertEquals(100, $paymentCreated->value);
    }

    public function testGetPaymentsByFilters(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $paymentCollection = PaymentDomain::where(['limit' => 3]);

        $this->assertInstanceOf(PaymentCollection::class, $paymentCollection);
        $this->assertCount(3, $paymentCollection->getData());
    }
}
