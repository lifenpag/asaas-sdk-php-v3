<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3;

use LifenPag\Asaas\V3\Interfaces\HydratableInterface;

class Hydratable implements HydratableInterface
{
    /**
     * Fill entity with parameters data
     *
     * @param array $parameters Entity parameters
     */
    public function hydrate(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            if (!property_exists($this, $property)) {
                continue;
            }

            $this->$property = $value;

            // Apply mutator
            $mutatorName = 'set' . ucfirst($this->convertToCamelCase($property));

            if (!method_exists($this, $mutatorName)) {
                continue;
            }

            call_user_func_array([$this, $mutatorName], [$value]);
        }
    }

    /**
     * Convert to CamelCase
     *
     * @param string $str Snake case string
     * @return string Camel case string
     */
    public function convertToCamelCase(string $str): string
    {
        $callback = static function ($match) {
            return strtoupper($match[2]);
        };

        return lcfirst(preg_replace_callback('/(^|_)([a-z])/', $callback, $str));
    }
}
