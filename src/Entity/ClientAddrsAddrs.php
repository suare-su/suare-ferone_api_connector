<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class ClientAddrsAddrs
{
    /** Id заказа */
    private int $orderId;

    /** Идентификатор улицы КЛАДР */
    private string $kladrStreetId;

    /** Город */
    private string $city;

    /** Улица */
    private string $street;

    /** Дом */
    private string $house;

    /** Квартира */
    private ?string $apartment;

    /** Подъезд */
    private ?string $entrance;

    /** Этаж */
    private ?string $floor;

    /** Полный адрес заказа до дома */
    private ?string $addr;

    /** Координаты широты заказа по полному адресу */
    private ?float $addrLat;

    /** Координаты долготы заказа по полному адресу */
    private ?float $addrLon;

    /** Точность опеределния координат, если 0 - координаты точны до дома, если больше - координаты не точные (до улицы, до города) */
    private ?int $addrAcc;

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getKladrStreetId(): string
    {
        return $this->kladrStreetId;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getHouse(): string
    {
        return $this->house;
    }

    public function getApartment(): ?string
    {
        return $this->apartment;
    }

    public function getEntrance(): ?string
    {
        return $this->entrance;
    }

    public function getFloor(): ?string
    {
        return $this->floor;
    }

    public function getAddr(): ?string
    {
        return $this->addr;
    }

    public function getAddrLat(): ?float
    {
        return $this->addrLat;
    }

    public function getAddrLon(): ?float
    {
        return $this->addrLon;
    }

    public function getAddrAcc(): ?int
    {
        return $this->addrAcc;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->orderId = (int) ($apiResponse['orderid'] ?? null);
        $this->kladrStreetId = (string) ($apiResponse['kladrstreetid'] ?? null);
        $this->city = (string) ($apiResponse['city'] ?? null);
        $this->street = (string) ($apiResponse['street'] ?? null);
        $this->house = (string) ($apiResponse['house'] ?? null);
        $this->apartment = isset($apiResponse['apartment']) ? (string) $apiResponse['apartment'] : null;
        $this->entrance = isset($apiResponse['entrance']) ? (string) $apiResponse['entrance'] : null;
        $this->floor = isset($apiResponse['floor']) ? (string) $apiResponse['floor'] : null;
        $this->addr = isset($apiResponse['addr']) ? (string) $apiResponse['addr'] : null;
        $this->addrLat = isset($apiResponse['addrlat']) ? (float) $apiResponse['addrlat'] : null;
        $this->addrLon = isset($apiResponse['addrlon']) ? (float) $apiResponse['addrlon'] : null;
        $this->addrAcc = isset($apiResponse['addracc']) ? (int) $apiResponse['addracc'] : null;
    }
}
