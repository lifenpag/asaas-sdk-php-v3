<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Interfaces;

interface CollectionInterface
{
    public function getData(): array;

    public function setData(CollectionInterface $data): CollectionInterface;
}
