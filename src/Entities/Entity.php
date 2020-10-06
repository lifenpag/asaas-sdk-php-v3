<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Entities;

use LifenPag\Asaas\V3\{
    Http\HttpClient,
    ResponseHandler,
    Hydratable
};

use DateTime;
use stdClass;

abstract class Entity extends Hydratable
{
    protected static $client;
    protected $exists = false;
    protected $primaryKeyValue = null;

    public function __construct(?stdClass $parameters = null)
    {
        if (!$parameters) {
            return $this;
        }

        $this->prepareHydrate($parameters);
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

    public function prepareHydrate($parameters): void
    {
        if (is_a($parameters, stdClass::class)) {
            $parameters = get_object_vars($parameters);
        }

        $this->hydrate($parameters);

        $this->exists = isset($this->id);
        $this->primaryKeyValue = $this->id ?? null;
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
        return DateTime::createFromFormat('Y-m-d', $stringDate);

        return $date;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     *
     * @throws EntityException
     * @return void
     */
    public function validate(): void
    {
        array_map(function ($property) {
            if (property_exists($this, $property) && $this->$property !== null) {
                return;
            }

            ResponseHandler::invalidEntity($property);
        }, static::FIELDS_REQUIRED);
    }
}
