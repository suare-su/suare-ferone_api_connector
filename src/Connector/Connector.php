<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Connector;

use DateTimeImmutable;
use SuareSu\FeroneApiConnector\Entity\City;
use SuareSu\FeroneApiConnector\Entity\Client;
use SuareSu\FeroneApiConnector\Entity\MenuItem;
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
        $response = $this->sendRequestInternal('GetCitiesList');

        return array_map(
            fn (array $item): City => new City($item),
            $response->getData()
        );
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
        $response = $this->sendRequestInternal('GetShopsList');

        return array_map(
            fn (array $item): Shop => new Shop($item),
            $response->getData()
        );
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

    /**
     * @return MenuItem[]
     */
    public function getMenu(int $cityId, bool $onlyVisible = true): array
    {
        $response = $this->sendRequestInternal(
            'GetMenu',
            [
                'CityID' => $cityId,
                'OnlyVisible' => $onlyVisible,
            ]
        );

        return array_map(
            fn (array $item): MenuItem => new MenuItem($item),
            $response->getData()
        );
    }

    public function getMenuLastChanged(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetMenuLastChanged')->getData();

        return new DateTimeImmutable($data['Changed'] ?? '');
    }

    /**
     * @return Client[]
     */
    public function getClientsList(?int $cityId = null, ?string $sex = null, ?string $birth = null, int $limit = 100, int $offset = 0): array
    {
        $params = [
            'CityID' => $cityId,
            'Sex' => $sex,
            'Birth' => $birth,
            'Limit' => $limit,
            'Offset' => $offset,
        ];
        $params = array_filter($params, fn ($item): bool => $item !== null);

        $response = $this->sendRequestInternal('GetClientsList', $params);

        return array_map(
            fn (array $item): Client => new Client($item),
            $response->getData()
        );
    }

    public function getClientInfo(int $id): Client
    {
        $response = $this->sendRequestInternal(
            'GetClientInfo',
            [
                'ClientID' => $id,
            ]
        );

        return new Client($response->getData());
    }

    public function getClientBonus(string $phoneNumber): int
    {
        $response = $this->sendRequestInternal(
            'GetClientBonus',
            [
                'Phone' => $phoneNumber,
            ]
        );
        $data = $response->getData();

        return (int) ($data['Balance'] ?? 0);
    }

    public function updateClientInfo(int $clientId, string $name, ?string $birth = null): void
    {
        $params = [
            'ClientID' => $clientId,
            'Name' => $name,
        ];
        if ($birth !== null) {
            $params['Birth'] = $birth;
        }

        $this->sendRequestInternal('UpdateClientInfo', $params);
    }

    private function sendRequestInternal(string $method, array $params = []): TransportResponse
    {
        $request = new TransportRequest($method, $params);

        return $this->transport->sendRequest($request);
    }
}
