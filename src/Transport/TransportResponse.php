<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

/**
 * Object that contains response data from API.
 */
class TransportResponse
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

    public function hasError(): bool
    {
        return !empty($this->payload['error']);
    }

    public function getError(): int
    {
        return (int) ($this->payload['error'] ?? 0);
    }

    public function getErrorDescription(): string
    {
        return (string) ($this->payload['errorDescription'] ?? '');
    }
}
