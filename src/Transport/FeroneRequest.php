<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

/**
 * Object that contains request data for API.
 */
class FeroneRequest
{
    private string $method;

    private array $params;

    public function __construct(string $method, array $params = [])
    {
        $this->method = $method;
        $this->params = $params;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
