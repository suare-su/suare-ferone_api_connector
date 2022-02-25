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

    /**
     * Return whole payload from response.
     *
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * Return only data parameter from payload.
     *
     * @return mixed[]
     */
    public function getData(): array
    {
        if (isset($this->payload['data']) && \is_array($this->payload['data'])) {
            return $this->payload['data'];
        }

        return [];
    }

    /**
     * Return true if there is an error set in the payload.
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return !empty($this->payload['error']);
    }

    /**
     * Return error number from payload.
     *
     * @return int
     */
    public function getError(): int
    {
        return (int) ($this->payload['error'] ?? 0);
    }

    /**
     * Return error description from payload.
     *
     * @return string
     */
    public function getErrorDescription(): string
    {
        return (string) ($this->payload['errorDescription'] ?? '');
    }
}
