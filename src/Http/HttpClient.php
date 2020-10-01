<?php

namespace LifenPag\ApiAsaas\V3\Http;

use \LifenPag\ApiAsaas\V3\ResponseHandler;

use \LifenPag\ApiAsaas\V3\Exceptions\ValidationsErrorException;

use \GuzzleHttp\Client;

use \GuzzleHttp\Exception\ClientException;

use \LifenPag\ApiAsaas\V3\Exceptions\InvalidJsonException;

use \Exception;

class HttpClient
{
    private const ENVIRONMENT_PRODUCTION_MODE = 'production';
    private const ENVIRONMENT_SANDBOX_MODE = 'sandbox';
    private const ENVIRONMENT_TESTS_MODE = 'tests';
    private const BASE_URL = [
       self::ENVIRONMENT_PRODUCTION_MODE => 'https://www.asaas.com/api/v3/',
       self::ENVIRONMENT_SANDBOX_MODE => 'https://sandbox.asaas.com/api/v3/',
       self::ENVIRONMENT_TESTS_MODE => 'https://private-anon-d5023560cd-asaasv3.apiary-mock.com/api/v3/'
    ];

    private $apiKey;
    private $options;

    public $environment;
    public $headers;

    protected static $http;
    protected static $obj;

    public function __construct(string $apiKey, string $environment = HttpClient::ENVIRONMENT_PRODUCTION_MODE)
    {
        if (isset(self::$http)) {
            return;
        }

        $this->validateEnvironmentVariable($environment)
            ->setEnvironment($environment)
            ->setApiKey($apiKey)
            ->setHeaders()
            ->setOptions();

        self::$http = new Client($this->getOptions());
    }

    public static function connect(string $apiKey = null, string $environment = null) {
        if (!isset(self::$http)) {
            new self($apiKey, $environment);
        }
    }

    public static function request($method, $uri, $payload = [])
    {
        try {
            $response = self::$http->request($method,$uri,$payload);

            return ResponseHandler::success((string)$response->getBody());
        } catch (ClientException $exception) {
            ResponseHandler::failure($exception);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function validateEnvironmentVariable(string $environment): self
    {
        if (!isset(self::BASE_URL[$environment])) {
            throw new ValidationsErrorException(ValidationsErrorException::INVALID_ENVIRONMENT_TYPE);
        }

        return $this;
    }

    /**
     * Get the value of environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Set the value of environment
     *
     * @return  self
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * Get the value of options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the value of options
     *
     * @return  self
     */
    public function setOptions()
    {
        $this->options['base_uri'] = self::BASE_URL[$this->environment];
        $this->options['headers'] = $this->getHeaders();

        return $this;
    }

    /**
     * Get the value of headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set the value of headers
     *
     * @return  self
     */
    public function setHeaders()
    {
        $this->headers['access_token'] = $this->apiKey;

        return $this;
    }

    /**
     * Set the value of apiKey
     *
     * @return  self
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}