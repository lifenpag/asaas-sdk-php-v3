<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3;

use LifenPag\Asaas\V3\{
    Http\HttpClient,
    Interfaces\Asaas as AsaasInterface
};

class Client extends HttpClient implements AsaasInterface
{
}
