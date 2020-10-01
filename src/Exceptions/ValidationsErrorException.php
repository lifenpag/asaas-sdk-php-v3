<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Exceptions;

use Exception;

class ValidationsErrorException extends Exception
{
    public const INVALID_ENVIRONMENT_TYPE = 'Invalid Environment Type';
    public const PRIMARY_KEY_MISSING = 'Missing Primay Key';
}
