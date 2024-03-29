<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Exception;

use SuareSu\FeroneApiConnector\Transport\TransportResponse;

/**
 * Exception from api layer.
 */
class ApiException extends Exception
{
    private ?TransportResponse $response = null;

    public static function create(string $message, int $code = null, TransportResponse $response = null): self
    {
        if ($code !== null) {
            $exception = new self($message, $code);
        } else {
            $exception = new self($message);
        }
        if ($response) {
            $exception->setResponse($response);
        }

        return $exception;
    }

    public function setResponse(TransportResponse $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse(): ?TransportResponse
    {
        return $this->response;
    }
}
