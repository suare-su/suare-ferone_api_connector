<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Connector;

use DateTimeImmutable;
use SuareSu\FeroneApiConnector\Exception\ApiException;
use SuareSu\FeroneApiConnector\Exception\TransportException;
use SuareSu\FeroneApiConnector\Transport\Transport;
use SuareSu\FeroneApiConnector\Transport\TransportRequest;
use SuareSu\FeroneApiConnector\Transport\TransportResponse;
use Throwable;

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

    public function getTokenExpiry(): DateTimeImmutable
    {
        $responseData = $this->sendRequestInternal('GetTokenExpiry')->getData();

        try {
            $dateExpire = new DateTimeImmutable((string) ($responseData['ExpiresOn'] ?? ''));
        } catch (Throwable $e) {
            throw new ApiException('Date in response for GetTokenExpiry is empty or broken', 0, $e);
        }

        return $dateExpire;
    }

    public function sendSMS(string $phoneNumber, string $message): void
    {
        $this->sendRequestInternal(
            'SendSMS',
            [
                'Phone' => $phoneNumber,
                'Message' => $message,
            ]
        );
    }

    private function sendRequestInternal(string $method, array $params = []): TransportResponse
    {
        $request = new TransportRequest($method, $params);

        return $this->transport->sendRequest($request);
    }
}
