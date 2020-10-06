<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Interfaces;

use LifenPag\Asaas\V3\Entities\Entity;

interface CollectionInterface
{
    public function getData(): array;

    public function setData(Entity $data);
}
