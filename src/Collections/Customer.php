<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Collections;

use LifenPag\Asaas\V3\{
    Collections\Collection,
    Domains\Customer as CustomerModel
};

class Customer extends Collection
{
    protected const MODEL = CustomerModel::class;
}
