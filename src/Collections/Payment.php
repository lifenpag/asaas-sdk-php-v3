<?php

namespace LifenPag\ApiAsaas\V3\Collections;

use \LifenPag\ApiAsaas\V3\Collections\Collection;

use \LifenPag\ApiAsaas\V3\Models\Payment as PaymentModel;

class Payment extends Collection
{
    protected const MODEL = PaymentModel::class;

    public function __construct($collection)
    {
        return parent::__construct($collection);
    }
}