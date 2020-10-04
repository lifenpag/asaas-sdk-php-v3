<?php declare(strict_types = 1);

namespace LifenPagTests\Units;

use PHPUnit\Framework\TestCase;

use LifenPag\Asaas\V3\{
    Client,
    Domains\Customer as CustomerDomain,
    Entities\Customer as CustomerEntity,
    Exceptions\ValidationsErrorException,
    Exceptions\AsaasException,
    Entities\Payment as PaymentEntity,
    Exceptions\EntityException,
};

use GuzzleHttp\Exception\ClientException;

use ArgumentCountError;

use InvalidArgumentException;

class ExceptionsTest extends TestCase
{
    public $apiKey = 'c0f2a7a68ec715cd5164fa729d0225a1645b1027cbc12f9ca7c83dead43952d1';
    public $environment = 'sandbox';

    public function testValidationErrorException(): void
    {
        $this->expectException(ValidationsErrorException::class);
        Client::connect('', 'error');
    }

    public function testArgumentCountError(): void
    {
        $this->expectException(ArgumentCountError::class);

        CustomerDomain::find();
    }

    public function testEntityException(): void
    {
        $this->expectException(EntityException::class);

        $customerEntity = new CustomerEntity();

        CustomerDomain::create($customerEntity);
    }

    public function testInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $paymentEntity = new PaymentEntity();

        CustomerDomain::create($paymentEntity);
    }

    public function testAsaasException(): void
    {
        $this->expectException(AsaasException::class);

        Client::connect($this->apiKey, $this->environment);

        $customer = new CustomerEntity();
        $customer->name = 'Homer Simpson';
        $customer->email = 'homer.simpson@lifenpag.com';
        $customer->cpfCnpj = '85267610054';

        $payment = new PaymentEntity();

        CustomerDomain::create($customer)->doPayment($payment);
    }
}
