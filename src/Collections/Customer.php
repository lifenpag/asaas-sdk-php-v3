<?php

namespace LifenPag\ApiAsaas\V3\Collections;

use \LifenPag\ApiAsaas\V3\Collections\Collection;

use \LifenPag\ApiAsaas\V3\Models\Customer as CustomerModel;

class Customer extends Collection
{
    protected const MODEL = CustomerModel::class;

    public function __construct($collection)
    {
        return parent::__construct($collection);
    }
}