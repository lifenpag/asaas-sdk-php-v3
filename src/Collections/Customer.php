<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Collections;

use LifenPag\Asaas\V3\{
    Collections\Collection,
    Entities\Customer as CustomerEntity
};

class Customer extends Collection
{
    protected const ENTITY = CustomerEntity::class;
}
