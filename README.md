# Library Asaas V3 for PHP

This is not an official Asaas library

## Installation

```
composer require lifenpag/asaas-php
```

## Create Client

```php
<?php
use LifenPag\Asaas\V3\Client;
use LifenPag\Asaas\V3\Domains\Customer as CustomerDomain;
use LifenPag\Asaas\V3\Entities\Customer as CustomerEntity;
use LifenPag\Asaas\V3\Collections\Customer as CustomerCollection;

Client::connect('your_api_key', 'sandbox|production');
```

## Customer

### Create Customer By Entity
```php
$customer = new CustomerEntity();
$customer->name = 'Homer Simpson';
$customer->email = 'homer.simpson@lifenpag.com';

$customerCreated = $customer->create();
```

### Create Customer By Domain
```php
$customer = new CustomerEntity();
$customer->name = 'Homer Simpson';
$customer->email = 'homer.simpson@lifenpag.com';

$customerCreated = CustomerDomain::create($customer)->get();
// or
$customer = new CustomerEntity((object) ["name" => "test", "email" => "email@test.com"];

$customerCreated = CustomerDomain::create($customer)->get();
```

### Update Customer
```php
$customerEntity = CustomerDomain::update(['name' => 'Homer Simpson Updated'], 'customer_id')->get();
```

### Update Customer Nested
```php
$customerEntity = new CustomerEntity();
$customerEntity->name = 'Homer Simpson';
$customerEntity->email = 'homer.simpson@lifenpag.com';
$customerEntity->cpfCnpj = '85267610054';

// Creating customer
$customer = CustomerDomain::create($customerEntity)->get();

// Updating object customer
$customerUpdated = $customer->update(['name' =>'Homer Jay Simpson']);
```

### Delete Customer
```php
$customerEntity = CustomerDomain::delete('customer_id')->get();
```

### Delete Customer Nested
```php
$customerEntity = CustomerDomain::find('customer_id')->get();

$customerEntityDeleted = $customerEntity->delete();
```

### Restore Customer
```php
$customerEntity = CustomerDomain::restore('customer_id')->get();
```

### Restore Customer Nested
```php
$customerEntity = CustomerDomain::find('customer_id')->get();

$customerEntityRestored = $customerEntity->restore();
```

### Get All Customers
```php
$customerCollection = CustomerDomain::all();
```

### Get Customers By Filters
```php
$customerCollection = CustomerDomain::where(['limit' => 3]);
```

### Update in loop Example
```php
$customerCollection = CustomerDomain::where(['limit' => 3]);

$customerCollection->map(function ($customer) {
	$customerUpdated = $customer->update(['name' => 'nested updated']);
});
```

## Payment
### Create Payment By Entity
```php
$payment = new PaymentEntity();
$payment->customer = 'customer_id';
$payment->billingType = 'BOLETO';
$payment->value = 100;
$payment->dueDate = (new DateTime())->format('Y-m-d');
$paymentCreated = $payment->create();
```

### Create Payment By Customer Domain (doPayment)
```php
$customer = new CustomerEntity();
$customer->name = 'Homer Simpson';
$customer->email = 'homer.simpson@lifenpag.com';
$customer->cpfCnpj = '85267610054';

$payment = new PaymentEntity();
$payment->billingType = 'BOLETO';
$payment->value = 100;
$payment->dueDate = (new DateTime())->format('Y-m-d');

$paymentCreated = CustomerDomain::create($customer)->doPayment($payment);
```

### Get Payment
```php
$payment = PaymentDomain::find('pay_id')->get();
```

### Get Payment With Digitable Line
```php
$payment = PaymentDomain::find('pay_id')->get()->populateDigitableLine();
```

## Credits

This project was created by Breno Vieira Soares from LifenPag. Feel free to contribute to the project
