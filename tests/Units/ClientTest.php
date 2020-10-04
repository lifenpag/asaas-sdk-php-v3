<?php declare(strict_types = 1);

namespace LifenPagTests\Units;

use PHPUnit\Framework\TestCase;

use LifenPag\Asaas\V3\{
    Client,
    Http\HttpClient,
    Exceptions\ValidationsErrorException,
    Domains\Customer,
};

class ClientTest extends TestCase
{
    public $apiKey = 'c0f2a7a68ec715cd5164fa729d0225a1645b1027cbc12f9ca7c83dead43952d1';
    public $environment = 'sandbox';

    public function testConnection(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customerDomain = new Customer();
        $customerEntity = $customerDomain->find('cus_000003281321')->get();

        $this->assertEquals('cus_000003281321', $customerEntity->id);
    }

    public function testConnectionDuplicate(): void
    {
        Client::connect($this->apiKey, $this->environment);
        Client::connect($this->apiKey, $this->environment);

        $customerDomain = new Customer();
        $customerEntity = $customerDomain->find('cus_000003281321')->get();

        $this->assertEquals('cus_000003281321', $customerEntity->id);
    }

    public function testConnectionByConstructor(): void
    {
        new HttpClient($this->apiKey, $this->environment);

        $customerDomain = new Customer();
        $customerEntity = $customerDomain->find('cus_000003281321')->get();

        $this->assertEquals('cus_000003281321', $customerEntity->id);
    }

    public function testConnectionByConstructorDuplicate(): void
    {
        new HttpClient($this->apiKey, $this->environment);
        new HttpClient($this->apiKey, $this->environment);

        $customerDomain = new Customer();
        $customerEntity = $customerDomain->find('cus_000003281321')->get();

        $this->assertEquals('cus_000003281321', $customerEntity->id);
    }
}
