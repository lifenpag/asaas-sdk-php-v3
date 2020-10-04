<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Exceptions;

use Exception;

final class EntityException extends Exception
{
    /**
     * @var string
     */
    protected $field;

    protected $description;

    /**
     * @param string $field
     */
    public function __construct($field, $description)
    {
        $this->typefield = $field;
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
        return $this->typefield .' '. $this->description;
    }
}
