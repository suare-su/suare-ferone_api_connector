<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

/**
 * Object that contains response data from API.
 */
class FeroneResponse
{
    private int $statusCode;

    private array $payload;

    public function __construct(int $statusCode, array $payload = [])
    {
        $this->statusCode = $statusCode;
        $this->payload = $payload;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getData(): array
    {
        if (isset($this->payload['data']) && \is_array($this->payload['data'])) {
            return $this->payload['data'];
        }

        return [];
    }
}
