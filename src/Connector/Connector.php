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
use SuareSu\FeroneApiConnector\Query\ClientBonusQuery;
use SuareSu\FeroneApiConnector\Query\ClientListQuery;
use SuareSu\FeroneApiConnector\Query\MenuQuery;
use SuareSu\FeroneApiConnector\Query\Query;
use SuareSu\FeroneApiConnector\Transport\Transport;
use SuareSu\FeroneApiConnector\Transport\TransportRequest;
use SuareSu\FeroneApiConnector\Transport\TransportResponse;

/**
 * Object that represents Ferone API methods.
 */
class Connector
{
    private Transport $transport;

    /**
     * @param Transport $transport
     */
    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * PingAPI method implementation.
     *
     * @return bool
     */
    public function pingApi(): bool
    {
        try {
            $this->sendRequestInternal('PingAPI');
        } catch (ApiException|TransportException $e) {
            return false;
        }

        return true;
    }

    /**
     * GetTokenExpiry method implementation.
     *
     * @return DateTimeImmutable
     */
    public function getTokenExpiry(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetTokenExpiry')->getData();

        return new DateTimeImmutable($data['ExpiresOn'] ?? '');
    }

    /**
     * SendSMS method implementation.
     *
     * @param string $phoneNumber
     * @param string $message
     */
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
     * GetCitiesList method implementation.
     *
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

    /**
     * GetCitiesList method implementation.
     *
     * @param int $id
     *
     * @return City
     */
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

    /**
     * GetCitiesLastChanged method implementation.
     *
     * @return DateTimeImmutable
     */
    public function getCitiesLastChanged(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetCitiesLastChanged')->getData();

        return new DateTimeImmutable($data['Changed'] ?? '');
    }

    /**
     * GetShopsList method implementation.
     *
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

    /**
     * GetShopInfo method implementation.
     *
     * @param int $id
     *
     * @return Shop
     */
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

    /**
     * GetShopsLastChanged method implementation.
     *
     * @return DateTimeImmutable
     */
    public function getShopsLastChanged(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetShopsLastChanged')->getData();

        return new DateTimeImmutable($data['Changed'] ?? '');
    }

    /**
     * GetMenu method implementation.
     *
     * @param MenuQuery $query
     *
     * @return MenuItem[]
     */
    public function getMenu(MenuQuery $query): array
    {
        $response = $this->sendRequestInternal('GetMenu', $query);

        return array_map(
            fn (array $item): MenuItem => new MenuItem($item),
            $response->getData()
        );
    }

    /**
     * GetMenuLastChanged method implementation.
     *
     * @return DateTimeImmutable
     */
    public function getMenuLastChanged(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetMenuLastChanged')->getData();

        return new DateTimeImmutable($data['Changed'] ?? '');
    }

    /**
     * GetClientsList method implementation.
     *
     * @param ClientListQuery $query
     *
     * @return Client[]
     */
    public function getClientsList(ClientListQuery $query): array
    {
        $response = $this->sendRequestInternal('GetClientsList', $query);

        return array_map(
            fn (array $item): Client => new Client($item),
            $response->getData()
        );
    }

    /**
     * GetClientInfo method implementation.
     *
     * @param int $id
     *
     * @return Client
     */
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

    /**
     * GetClientBonus method implementation.
     *
     * @param ClientBonusQuery $query
     *
     * @return int
     */
    public function getClientBonus(ClientBonusQuery $query): int
    {
        $data = $this->sendRequestInternal('GetClientBonus', $query)->getData();

        return (int) ($data['Balance'] ?? 0);
    }

    /**
     * UpdateClientInfo method implementation.
     *
     * @param int         $clientId
     * @param string      $name
     * @param string|null $birth
     */
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

    /**
     * Create and send request using transport.
     *
     * @param string      $method
     * @param array|Query $params
     *
     * @return TransportResponse
     */
    private function sendRequestInternal(string $method, $params = []): TransportResponse
    {
        if ($params instanceof Query) {
            $params = $params->getParams();
        }
        $request = new TransportRequest($method, $params);

        return $this->transport->sendRequest($request);
    }
}
