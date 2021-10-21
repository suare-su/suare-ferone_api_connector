<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Connector;

use SuareSu\FeroneApiConnector\Exception\ApiException;
use SuareSu\FeroneApiConnector\Exception\TransportException;
use SuareSu\FeroneApiConnector\Transport\Transport;
use SuareSu\FeroneApiConnector\Transport\TransportRequest;
use SuareSu\FeroneApiConnector\Transport\TransportResponse;

/**
 * Object that represents Ferone API methods.
 */
class Connector
{
    private Transport $transport;

    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    public function pingApi(): bool
    {
        try {
            $this->sendRequestInternal('PingAPI');
        } catch (ApiException|TransportException $e) {
            return false;
        }

        return true;
    }

    private function sendRequestInternal(string $method, array $params = []): TransportResponse
    {
        $request = new TransportRequest($method, $params);

        return $this->transport->sendRequest($request);
    }
}
