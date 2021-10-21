<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;

/**
 * Factory object that can create a transport object using different clients.
 */
class TransportFactory
{
    private string $url = '';

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function createForGuzzleClient(Client $client): Transport
    {
        $config = new TransportConfig($this->url);
        $factory = new HttpFactory();

        return new Transport($config, $client, $factory, $factory);
    }
}
