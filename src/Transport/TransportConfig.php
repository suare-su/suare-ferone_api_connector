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

    public function __construct(string $url, string $authKey)
    {
        if (!preg_match('#^https?://[^\.]+\.[^\.]+.*#', $url)) {
            $message = sprintf('Correct absolute url is required. Got: %s', $url);
            throw new InvalidArgumentException($message);
        }

        $this->url = trim($url, " \n\r\t\v\0/\\") . '/';
        $this->authKey = $authKey;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getAuthKey(): string
    {
        return $this->authKey;
    }
}
