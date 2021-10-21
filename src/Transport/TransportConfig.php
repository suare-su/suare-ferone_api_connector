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
            $message = "Correct absolute url is required. Got: {$url}";
            throw new InvalidArgumentException($message);
        }

        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
