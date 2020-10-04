<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Domains;

use LifenPag\Asaas\V3\{
    Collections\Payment as PaymentCollection,
    Entities\Payment as PaymentEntity,
    Traits\Payment as PaymentTrait,
};

final class Payment extends Domain
{
    use PaymentTrait;

    public const MODEL_NAME = 'payments';

    public static $collectionClass = PaymentCollection::class;
    public static $entityClass = PaymentEntity::class;
}
