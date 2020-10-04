<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Interfaces;

interface HydratableInterface
{
    public function hydrate(array $parameters): void;

    public function convertToCamelCase(string $str): string;
}
