<?php

namespace LifenPag\ApiAsaas\V3\Models;

use \LifenPag\ApiAsaas\V3\Exceptions\ValidationsErrorException;

use \LifenPag\ApiAsaas\V3\Http\HttpClient;

abstract class Model
{
    private static $client;

    private static $exists;
    private static $primary_key_value;

    public function __construct($parameters = null)
    {
        if(!$parameters)
        {
            return $this;
        }

        $this->prepareBuild($parameters);
    }

    public static function create(array $request) {

        $response = HttpClient::request(
            'POST',
            static::MODEL_NAME,
            ['json' => $request]
        );

        return new static($response);
    }

    public static function update(array $request, string $id = null) {
        self::checkPrimary($id);

        $response = HttpClient::request(
            'POST',
            static::MODEL_NAME.'/'. self::$primary_key_value,
            ['json' => $request]
        );

        return new static($response);
    }

    public function delete(string $id = null) {
        self::checkPrimary($id);

        $response = HttpClient::request(
            'DELETE',
            static::MODEL_NAME.'/'. self::$primary_key_value,
            ['json' => $request]
        );

        return new static($response);
    }

    public function restore(string $id = null) {
        self::checkPrimary($id);

        $response = HttpClient::request(
            'POST',
            static::MODEL_NAME.'/'. self::$primary_key_value . '/restore',
            ['json' => $request]
        );

        return new static($response);
    }

    public static function find(string $id) {
        $response = HttpClient::request(
            'GET',
            static::MODEL_NAME.'/'. $id,
        );

        return new static($response);
    }

    public static function all(array $filters = ['limit' => -1]) {
        $response = HttpClient::request(
            'GET',
            static::MODEL_NAME,
            ['query' => $filters]
        );
        $class = (static::class)::$collection;
        return new static($response);
    }

    public static function where(array $filters) {

        return self::all($filters);
    }

    public function prepareBuild($parameters) {
        if($parameters instanceof \stdClass)
        {
            $parameters = get_object_vars($parameters);
        }

        $this->build($parameters);

        self::$exists = isset($this->id);
        self::$primary_key_value = $this->id ?? null;
    }

    /**
     * Fill entity with parameters data
     *
     * @param  array  $parameters  Entity parameters
     */
    public function build(array $parameters)
    {
        foreach($parameters as $property => $value)
        {
            if(property_exists($this, $property))
            {
                $this->$property = $value;

                // Apply mutator

                $mutator = 'set' . ucfirst(static::convertToCamelCase($property));

                if(method_exists($this, $mutator))
                {
                    call_user_func_array(array($this, $mutator), [$value]);
                }
            }
        }
    }

    /**
     * Convert date string do DateTime Object
     *
     * @param  string|null  $date  DateTime string
     * @return \DateTime
     */
    protected static function convertDateTime($date, $format = 'Y-m-d')
    {
        if(!$date)
        {
            return;
        }

        $date = \DateTime::createFromFormat($format, $date);

        if(!$date)
        {
            return;
        }

        return $date;
    }

    /**
     * Convert to CamelCase
     *
     * @param   string  $str  Snake case string
     * @return  string  Camel case string
     */
    protected static function convertToCamelCase($str)
    {
        $callback = function($match)
        {
            return strtoupper($match[2]);
        };

        return lcfirst(preg_replace_callback('/(^|_)([a-z])/', $callback, $str));
    }

    private static function checkPrimary($id) {
        if ($id === null && !self::$exists) {
            throw new ValidationsErrorException(ValidationsErrorException::PRIMARY_KEY_MISSING);
        }

        self::$primary_key_value = $id ?? self::$primary_key_value;
    }
}