<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Exception;

use SuareSu\FeroneApiConnector\Transport\TransportRequest;

/**
 * Exception from transport layer. E.g. from http client.
 */
class TransportException extends Exception
{
    private ?TransportRequest $request = null;

    private ?string $response = null;

    public function setRequest(?TransportRequest $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getRequest(): ?TransportRequest
    {
        return $this->request;
    }

    public function setResponse(?string $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }
}
