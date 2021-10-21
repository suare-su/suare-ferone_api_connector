<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

/**
 * Object that contains response data from API.
 */
class FeroneResponse
{
    private array $payload;

    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
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
