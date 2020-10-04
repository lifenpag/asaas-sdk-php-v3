<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Entities;

use LifenPag\Asaas\V3\{
    Http\HttpClient,
    ResponseHandler,
};

use DateTime;
use stdClass;

abstract class Entity
{
    protected static $client;
    protected $exists = false;
    protected $primaryKeyValue = null;

    public function __construct(?stdClass $parameters = null)
    {
        if (!$parameters) {
            return $this;
        }

        $this->prepareBuild($parameters);
    }

    public function create(): self
    {
        $this->validate();

        $response = HttpClient::create($this, static::$modelName);

        return new static($response);
    }

    public function update(array $request): self
    {
        $response = HttpClient::update($request, $this->primaryKeyValue, static::$modelName);

        return new static($response);
    }

    public function delete(): self
    {
        $response = HttpClient::delete($this->primaryKeyValue, static::$modelName);

        return new static($response);
    }

    public function restore(): self
    {
        $response = HttpClient::restore($this->primaryKeyValue, static::$modelName);

        return new static($response);
    }

    public function prepareBuild($parameters): void
    {
        if (is_a($parameters, stdClass::class)) {
            $parameters = get_object_vars($parameters);
        }

        $this->build($parameters);

        $this->exists = isset($this->id);
        $this->primaryKeyValue = $this->id ?? null;
    }

    /**
     * Fill entity with parameters data
     *
     * @param array $parameters Entity parameters
     */
    public function build(array $parameters): void
    {
        foreach ($parameters as $property => $value) {
            if (!property_exists($this, $property)) {
                continue;
            }

            $this->$property = $value;

            // Apply mutator
            $mutatorName = 'set' . ucfirst(static::convertToCamelCase($property));

            if (!method_exists($this, $mutatorName)) {
                continue;
            }

            call_user_func_array([$this, $mutatorName], [$value]);
        }
    }

    /**
     * Convert date string do DateTime Object
     *
     * @param string $date DateTime string
     *
     * @return DateTime
     */
    protected static function convertDateTime(
        string $stringDate
    ): DateTime {
        $date = DateTime::createFromFormat('Y-m-d', $stringDate);

        if (!$date) {
            $date = DateTime::createFromFormat('d/m/Y', $stringDate);
        }

        return $date;
    }

    /**
     * Convert to CamelCase
     *
     * @param string $str Snake case string
     * @return string Camel case string
     */
    protected static function convertToCamelCase($str): string
    {
        $callback = static function ($match) {
            return strtoupper($match[2]);
        };

        return lcfirst(preg_replace_callback('/(^|_)([a-z])/', $callback, $str));
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function validate(): void
    {
        foreach (static::FIELDS_REQUIRED as $property) {
            if (!property_exists($this, $property) || $this->$property === null) {
                ResponseHandler::invalidEntity($property);
                exit;
            }
        }
    }
}
