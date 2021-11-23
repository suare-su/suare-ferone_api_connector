<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

use InvalidArgumentException;

/**
 * Object that contains all data required for transport.
 */
class TransportConfig
{
    private string $url;

    private string $authKey;

    private int $timeout;

    private int $retries;

    public function __construct(
        string $url,
        string $authKey,
        int $timeout = 5,
        int $retries = 1
    ) {
        if (!preg_match('#^https?://[^\.]+\.[^\.]+.*#', $url)) {
            $message = sprintf('Correct absolute url is required. Got: %s', $url);
            throw new InvalidArgumentException($message);
        }

        $this->url = trim($url, " \n\r\t\v\0/\\") . '/';
        $this->authKey = $authKey;
        $this->timeout = $timeout;
        $this->retries = $retries;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getAuthKey(): string
    {
        return $this->authKey;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getRetries(): int
    {
        return $this->retries;
    }
}
