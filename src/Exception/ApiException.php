<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Exception;

/**
 * Exception from api layer.
 */
class ApiException extends Exception
{
    private const ERROR_STSTUSES = [
        400 => 'Bad or poorly structured request',
        401 => 'Request isn\'t authorized',
        403 => 'Method is forbidden',
        404 => 'Method isn\'t found',
        500 => 'Server side error',
        503 => 'API is unavailable',
    ];

    public static function invalidStatusCode(int $statusCode, array $payload): self
    {
        $message = "API has returned bas status code {$statusCode}";
        if (isset(self::ERROR_STSTUSES[$statusCode])) {
            $message .= '(' . self::ERROR_STSTUSES[$statusCode] . ')';
        }

        $message .= !empty($payload['errorDescription']) ? ": {$payload['errorDescription']}" : '';

        return new self($message);
    }

    public static function errorInResponse(array $payload): self
    {
        $message = 'API has returned an error';
        $message .= !empty($payload['error']) ? "({$payload['error']})" : '';
        $message .= !empty($payload['errorDescription']) ? ": {$payload['errorDescription']}" : '';

        return new self($message);
    }
}
