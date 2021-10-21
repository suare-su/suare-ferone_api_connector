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

    public function __construct(string $url)
    {
        if (!preg_match('#^https?://[^\.]+\.[^\.]+.*#', $url)) {
            $message = sprintf('Correct absolute url is required. Got: %s', $url);
            throw new InvalidArgumentException($message);
        }

        $this->url = trim($url, " \n\r\t\v\0/\\") . '/';
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
