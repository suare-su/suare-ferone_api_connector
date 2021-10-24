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
        $this->orderId = (int) ($apiResponse['OrderID'] ?? null);
        $this->kladrStreetId = (string) ($apiResponse['KladrStreetID'] ?? null);
        $this->city = (string) ($apiResponse['City'] ?? null);
        $this->street = (string) ($apiResponse['Street'] ?? null);
        $this->house = (string) ($apiResponse['House'] ?? null);
        $this->apartment = isset($apiResponse['Apartment']) ? (string) $apiResponse['Apartment'] : null;
        $this->entrance = isset($apiResponse['Entrance']) ? (string) $apiResponse['Entrance'] : null;
        $this->floor = isset($apiResponse['Floor']) ? (string) $apiResponse['Floor'] : null;
        $this->addr = isset($apiResponse['Addr']) ? (string) $apiResponse['Addr'] : null;
        $this->addrLat = isset($apiResponse['AddrLat']) ? (float) $apiResponse['AddrLat'] : null;
        $this->addrLon = isset($apiResponse['AddrLon']) ? (float) $apiResponse['AddrLon'] : null;
        $this->addrAcc = isset($apiResponse['AddrAcc']) ? (int) $apiResponse['AddrAcc'] : null;
    }
}
