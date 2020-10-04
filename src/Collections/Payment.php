<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Collections;

use LifenPag\Asaas\V3\{
    Collections\Collection,
    Entities\Payment as PaymentEntity
};

class Payment extends Collection
{
    protected const ENTITY = PaymentEntity::class;
}
