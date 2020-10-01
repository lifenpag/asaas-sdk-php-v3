<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3;

use Throwable;
use stdClass;

use GuzzleHttp\Exception\ClientException;

use LifenPag\Asaas\V3\Exceptions\{
    AsaasException,
    InvalidJsonException
};

class ResponseHandler
{
    /**
     * @param string $payload
     *
     * @throws InvalidJsonException
     *
     * @return stdClass
     */
    public static function success($payload)
    {
        return self::toJson($payload);
    }

    /**
     * @param ClientException $originalException
     *
     * @throws AsaasException
     * @return void
     */
    public static function failure(Throwable $originalException)
    {
        throw self::parseException($originalException);
    }

    /**
     * @param ClientException $guzzleException
     *
     * @return Throwable
     */
    protected static function parseException(ClientException $guzzleException)
    {
        $response = $guzzleException->getResponse();

        if (is_null($response)) {
            return $guzzleException;
        }

        $body = $response->getBody()->getContents();

        try {
            $jsonError = self::toJson($body);
        } catch (InvalidJsonException $invalidJson) {
            return $guzzleException;
        }

        return new AsaasException(
            $jsonError->errors[0]->code,
            $jsonError->errors[0]->description,
        );
    }

    /**
     * @param string $json
     *
     * @return stdClass
     */
    protected static function toJson($json): stdClass
    {
        $result = json_decode($json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException(json_last_error_msg());
        }

        return $result;
    }
}
