<?php

namespace LifenPag\ApiAsaas\V3\Exceptions;

use Exception;

final class AsaasException extends Exception
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $description;


    /**
     * @param string $type
     * @param string $description
     */
    public function __construct($type, $description)
    {
        $this->type = $type;
        $this->description = $description;

        $exceptionMessage = $this->buildExceptionMessage();

        parent::__construct($exceptionMessage);
    }

    /**
     * @return string
     */
    private function buildExceptionMessage()
    {
        return sprintf(
            'ERROR TYPE: %s. DESCRIPTION: %s',
            $this->type,
            $this->description
        );
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getParameterName()
    {
        return $this->description;
    }
}