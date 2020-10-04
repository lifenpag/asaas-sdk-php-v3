<?php declare(strict_types = 1);

namespace LifenPagTests\Units;

use PHPUnit\Framework\TestCase;

use LifenPag\Asaas\V3\Client;

use LifenPag\Asaas\V3\Domains\Customer as CustomerDomain;

use LifenPag\Asaas\V3\Entities\Customer as CustomerEntity;

use LifenPag\Asaas\V3\Collections\Customer as CustomerCollection;

class CustomerTest extends TestCase
{
    public $apiKey = 'c0f2a7a68ec715cd5164fa729d0225a1645b1027cbc12f9ca7c83dead43952d1';
    public $environment = 'sandbox';

    public function testCustomerDomainCreate(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customerDomain = new CustomerDomain();
        $this->assertInstanceOf(CustomerDomain::class, $customerDomain);
    }

    public function testCustomerEntityCreate(): void
    {
        $customerEntity = new CustomerEntity();

        $this->assertInstanceOf(CustomerEntity::class, $customerEntity);
    }

    public function testCreateCustomerByEntity(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customer = new CustomerEntity();
        $customer->name = 'Homer Simpson';
        $customer->email = 'homer.simpson@lifenpag.com';
        $customerCreated = $customer->create();

        $this->assertInstanceOf(CustomerEntity::class, $customerCreated);
        $this->assertNotNull($customerCreated->id);
        $this->assertEquals('homer.simpson@lifenpag.com', $customerCreated->email);
        $this->assertEquals('Homer Simpson', $customerCreated->name);
    }

    public function testCreateCustomerByDomain(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customer = new CustomerEntity();
        $customer->name = 'Homer Simpson';
        $customer->email = 'homer.simpson@lifenpag.com';

        $customerCreated = CustomerDomain::create($customer)->get();

        $this->assertInstanceOf(CustomerEntity::class, $customerCreated);
        $this->assertNotNull($customerCreated->id);
        $this->assertEquals('homer.simpson@lifenpag.com', $customerCreated->email);
        $this->assertEquals('Homer Simpson', $customerCreated->name);
    }

    public function testUpdateCustomer(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customerEntity = CustomerDomain::update(['name' => 'Homer Simpson'], 'cus_000003281321')->get();

        $this->assertInstanceOf(CustomerEntity::class, $customerEntity);
    }

    public function testUpdateCustomerNested(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customerEntity = new CustomerEntity();
        $customerEntity->name = 'Homer Simpson';
        $customerEntity->email = 'homer.simpson@lifenpag.com';
        $customerEntity->cpfCnpj = '85267610054';

        $customer = CustomerDomain::create($customerEntity)->get();
        $customerUpdated = $customer->update(['name' =>'Homer Jay Simpson']);

        $this->assertInstanceOf(CustomerEntity::class, $customerUpdated);
        $this->assertEquals('Homer Jay Simpson', $customerUpdated->name);
    }

    public function testDeleteCustomer(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customerEntity = CustomerDomain::delete('cus_000003281321')->get();

        $this->assertInstanceOf(CustomerEntity::class, $customerEntity);
        $this->assertEquals(true, $customerEntity->deleted);
    }

    public function testDeleteCustomerNested(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customerEntity = CustomerDomain::find('cus_000003281321')->get();

        $customerEntityDeleted = $customerEntity->delete();

        $this->assertInstanceOf(CustomerEntity::class, $customerEntityDeleted);
        $this->assertEquals(true, $customerEntityDeleted->deleted);
    }

    public function testRestoreCustomer(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customerEntity = CustomerDomain::restore('cus_000003281321')->get();

        $this->assertInstanceOf(CustomerEntity::class, $customerEntity);
        $this->assertEquals(false, $customerEntity->deleted);
    }

    public function testRestoreCustomerNested(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customerEntity = CustomerDomain::restore('cus_000003281321')->get();

        $customerEntityRestored = $customerEntity->restore();

        $this->assertInstanceOf(CustomerEntity::class, $customerEntityRestored);
        $this->assertEquals(false, $customerEntityRestored->deleted);
    }

    public function testGetAllCustomers(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customerCollection = CustomerDomain::all();

        $this->assertInstanceOf(CustomerCollection::class, $customerCollection);
    }

    public function testGetCustomersByFilters(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customerCollection = CustomerDomain::where(['limit' => 3]);

        $this->assertInstanceOf(CustomerCollection::class, $customerCollection);
        $this->assertCount(3, $customerCollection->getData());
    }

    public function testGetCustomersByFiltersAndUpdateNested(): void
    {
        Client::connect($this->apiKey, $this->environment);

        $customerCollection = CustomerDomain::where(['limit' => 3]);

        $customerCollection->map(function ($customer) {
            $customerUpdated = $customer->update(['name' => 'nested updated']);

            $this->assertInstanceOf(CustomerEntity::class, $customerUpdated);
            $this->assertEquals('nested updated', $customerUpdated->name);
        });

        $this->assertInstanceOf(CustomerCollection::class, $customerCollection);
        $this->assertCount(3, $customerCollection->getData());
    }
}
