<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Connector;

use DateTimeImmutable;
use SuareSu\FeroneApiConnector\Entity\City;
use SuareSu\FeroneApiConnector\Entity\Shop;
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

    public function getTokenExpiry(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetTokenExpiry')->getData();

        return new DateTimeImmutable($data['ExpiresOn'] ?? '');
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

    /**
     * @return City[]
     */
    public function getCitiesList(): array
    {
        $data = $this->sendRequestInternal('GetCitiesList')->getData();
        $callback = fn (array $item): City => new City($item);

        return array_map($callback, $data);
    }

    public function getCityInfo(int $id): City
    {
        $response = $this->sendRequestInternal(
            'GetCityInfo',
            [
                'CityID' => $id,
            ]
        );

        return new City($response->getData());
    }

    public function getCitiesLastChanged(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetCitiesLastChanged')->getData();

        return new DateTimeImmutable($data['Changed'] ?? '');
    }

    /**
     * @return Shop[]
     */
    public function getShopsList(): array
    {
        $data = $this->sendRequestInternal('GetShopsList')->getData();
        $callback = fn (array $item): Shop => new Shop($item);

        return array_map($callback, $data);
    }

    public function getShopInfo(int $id): Shop
    {
        $response = $this->sendRequestInternal(
            'GetShopInfo',
            [
                'ShopID' => $id,
            ]
        );

        return new Shop($response->getData());
    }

    public function getShopsLastChanged(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetShopsLastChanged')->getData();

        return new DateTimeImmutable($data['Changed'] ?? '');
    }

    private function sendRequestInternal(string $method, array $params = []): TransportResponse
    {
        $request = new TransportRequest($method, $params);

        return $this->transport->sendRequest($request);
    }
}
