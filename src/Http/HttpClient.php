<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Http;

use stdClass;

use GuzzleHttp\{
    Client,
    Exception\ClientException
};

use LifenPag\Asaas\V3\{
    Entities\Entity,
    ResponseHandler,
    Exceptions\ValidationsErrorException,

};

class HttpClient
{
    protected const ENVIRONMENT_PRODUCTION_MODE = 'production';
    protected const ENVIRONMENT_SANDBOX_MODE = 'sandbox';
    protected const ENVIRONMENT_TESTS_MODE = 'tests';
    protected const BASE_URL = [
       self::ENVIRONMENT_PRODUCTION_MODE => 'https://www.asaas.com/api/v3/',
       self::ENVIRONMENT_SANDBOX_MODE => 'https://sandbox.asaas.com/api/v3/',
       self::ENVIRONMENT_TESTS_MODE => 'https://private-anon-a054b7305d-asaasv3.apiary-mock.com',
    ];

    protected $apiKey;
    protected $options = [];

    public $environment;
    public $headers = [];

    protected static $http;
    protected static $obj;

    public function __construct(
        string $apiKey,
        string $environment = self::ENVIRONMENT_PRODUCTION_MODE
    ) {
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

    public static function connect(
        string $apiKey,
        string $environment
    ) {
        if (isset(self::$http)) {
            return;
        }

        new self($apiKey, $environment);
    }

    /**
     *
     * @throws Throwable
     * @return stdClass
     */
    public static function request(
        string $method,
        string $uri,
        array $payload = []
    ): stdClass {
        try {
            $response = self::$http->request($method, $uri, $payload);

            return ResponseHandler::success((string) $response->getBody());
        } catch (ClientException $exception) {
            ResponseHandler::failure($exception);
        }
    }

    protected function validateEnvironmentVariable(
        string $environment
    ): self {
        if (!isset(self::BASE_URL[$environment])) {
            throw new ValidationsErrorException(ValidationsErrorException::INVALID_ENVIRONMENT_TYPE);
        }

        return $this;
    }

    public static function create(Entity $entity, string $modelName): stdClass
    {
        return self::request(
            'POST',
            $modelName,
            ['json' => $entity->toArray()],
        );
    }

    public static function delete(string $id, string $modelName): stdClass
    {
        return self::request(
            'DELETE',
            $modelName . '/' . $id,
        );
    }

    public static function restore(string $id, string $modelName): stdClass
    {
        return self::request(
            'POST',
            $modelName . '/' . $id . '/restore',
        );
    }

    public static function update(array $request, string $id, string $modelName): stdClass
    {
        return self::request(
            'POST',
            $modelName . '/' . $id,
            ['json' => $request],
        );
    }

    public static function all(array $filters, string $modelName): stdClass
    {
        return self::request(
            'GET',
            $modelName,
            ['query' => $filters],
        );
    }

    /**
     * Get the value of environment
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * Set the value of environment
     *
     * @return self
     */
    public function setEnvironment($environment): self
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * Get the value of options
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set the value of options
     *
     * @return self
     */
    public function setOptions(): self
    {
        $this->options['base_uri'] = self::BASE_URL[$this->getEnvironment()];
        $this->options['headers'] = $this->getHeaders();

        return $this;
    }

    /**
     * Get the value of headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the value of headers
     *
     * @return self
     */
    public function setHeaders(): self
    {
        $this->headers['access_token'] = $this->apiKey;
        $this->headers['content-type'] = 'application/json';

        return $this;
    }

    /**
     * Set the value of apiKey
     *
     * @return self
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}
