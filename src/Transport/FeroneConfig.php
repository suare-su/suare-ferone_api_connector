<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

/**
 * Object that contains all data required for transport.
 */
class FeroneConfig
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
