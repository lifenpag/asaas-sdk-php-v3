<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Domains;

use LifenPag\Asaas\V3\{
    Collections\Customer as CustomerCollection,
    Entities\Customer as CustomerEntity,
    Entities\Payment as PaymentEntity,
    Traits\Customer as CustomerTrait,
};


final class Customer extends Domain
{
    use CustomerTrait;

    public static $collectionClass = CustomerCollection::class;
    public static $entityClass = CustomerEntity::class;

    public function doPayment(PaymentEntity $paymentEntity): PaymentEntity
    {
        $paymentEntity->customer = self::$entity->id;

        return $paymentEntity->create();
    }
}
