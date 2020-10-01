<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Domains;

use DateTime;
use stdClass;

use LifenPag\Asaas\V3\{
    Collections\Collection,
    Exceptions\ValidationsErrorException,
    Http\HttpClient
};

abstract class Model
{
    protected static $client;
    protected static $exists;
    protected static $primaryKeyValue;

    public function __construct(array $parameters = [])
    {
        if (!$parameters) {
            return $this;
        }

        $this->prepareBuild($parameters);
    }

    public static function create(array $request): self
    {
        $response = HttpClient::request(
            'POST',
            static::MODEL_NAME,
            ['json' => $request],
        );

        return new static($response);
    }

    public static function update(
        array $request,
        string $id
    ): self {
        self::checkPrimary($id);

        $response = HttpClient::request(
            'POST',
            static::MODEL_NAME . '/' . self::$primaryKeyValue,
            ['json' => $request],
        );

        return new static($response);
    }

    public function delete(string $id): self
    {
        self::checkPrimary($id);

        $response = HttpClient::request(
            'DELETE',
            static::MODEL_NAME . '/' . self::$primaryKeyValue,
            ['json' => $request],
        );

        return new static($response);
    }

    public function restore(string $id): self
    {
        self::checkPrimary($id);

        $response = HttpClient::request(
            'POST',
            static::MODEL_NAME . '/' . self::$primaryKeyValue . '/restore',
            ['json' => $request],
        );

        return new static($response);
    }

    public static function find(string $id): self
    {
        $response = HttpClient::request(
            'GET',
            static::MODEL_NAME . '/' . $id,
        );

        return new static($response);
    }

    public static function all(array $filters = ['limit' => -1]): Collection
    {
        $response = HttpClient::request(
            'GET',
            static::MODEL_NAME,
            ['query' => $filters],
        );

        return static::$collection($response);
    }

    public static function where(array $filters = []): Collection
    {
        return self::all($filters);
    }

    public function prepareBuild($parameters): void
    {
        if (is_a($parameters, stdClass::class)) {
            $parameters = get_object_vars($parameters);
        }

        $this->build($parameters);

        self::$exists = isset($this->id);
        self::$primaryKeyValue = $this->id ?? null;
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
        string $date,
        $format = 'Y-m-d'
    ): DateTime {
        return DateTime::createFromFormat($format, $date);
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

    protected static function checkPrimary($id): bool
    {
        if ($id === null && !self::$exists) {
            throw new ValidationsErrorException(ValidationsErrorException::PRIMARY_KEY_MISSING);
        }

        self::$primaryKeyValue = $id ?? self::$primaryKeyValue;
    }
}
