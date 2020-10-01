<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Exceptions;

use Exception;

final class AsaasException extends Exception
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $description;

    /**
     * @param string $type
     * @param string $description
     */
    public function __construct($type, $description)
    {
        $this->type = $type;
        $this->description = $description;

        parent::__construct((string) $this);
    }

    /**
     * Convert exception into string
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'ERROR TYPE: ' . $this->type . '. DESCRIPTION: ' . $this->description;
    }

    /**
     * Get Exception Type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get Parameter Name
     *
     * @return string
     */
    public function getParameterName(): string
    {
        return $this->description;
    }
}
