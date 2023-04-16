<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

/**
 * Object that contains all data required for transport.
 */
class TransportConfig
{
    private string $url;

    private string $authKey;

    private int $timeout;

    private int $retries;

    private bool $debug;

    public function __construct(
        string $url,
        string $authKey,
        int $timeout = 5,
        int $retries = 1,
        bool $debug = false
    ) {
        if (!preg_match('#^https?://[^\.]+\.[^\.]+.*#', $url)) {
            $message = sprintf('Correct absolute url is required. Got: %s', $url);
            throw new \InvalidArgumentException($message);
        }

        if ($timeout <= 0) {
            $message = sprintf('Timeout must be more than 0. Got: %s', $timeout);
            throw new \InvalidArgumentException($message);
        }

        if ($retries <= 0) {
            $message = sprintf('Retries must be more than 0. Got: %s', $timeout);
            throw new \InvalidArgumentException($message);
        }

        $this->url = trim($url, " \n\r\t\v\0/\\") . '/';
        $this->authKey = $authKey;
        $this->timeout = $timeout;
        $this->retries = $retries;
        $this->debug = $debug;
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

    public function getDebug(): bool
    {
        return $this->debug;
    }
}
