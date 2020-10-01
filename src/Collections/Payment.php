<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Collections;

use LifenPag\Asaas\V3\{
    Collections\Collection,
    Domains\Payment as PaymentModel
};

class Payment extends Collection
{
    protected const MODEL = PaymentModel::class;
}
