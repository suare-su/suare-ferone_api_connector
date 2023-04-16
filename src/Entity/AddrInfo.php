<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class AddrInfo implements \JsonSerializable
{
    /** Идентификатор улицы КЛАДР */
    private ?string $kladr;

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

    /** Город */
    private ?string $city;

    public function getKladr(): ?string
    {
        return $this->kladr;
    }

    public function setKladr(?string $value): self
    {
        $this->kladr = $value;

        return $this;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $value): self
    {
        $this->street = $value;

        return $this;
    }

    public function getHouse(): string
    {
        return $this->house;
    }

    public function setHouse(string $value): self
    {
        $this->house = $value;

        return $this;
    }

    public function getApartment(): ?string
    {
        return $this->apartment;
    }

    public function setApartment(?string $value): self
    {
        $this->apartment = $value;

        return $this;
    }

    public function getEntrance(): ?string
    {
        return $this->entrance;
    }

    public function setEntrance(?string $value): self
    {
        $this->entrance = $value;

        return $this;
    }

    public function getFloor(): ?string
    {
        return $this->floor;
    }

    public function setFloor(?string $value): self
    {
        $this->floor = $value;

        return $this;
    }

    public function getAddr(): ?string
    {
        return $this->addr;
    }

    public function setAddr(?string $value): self
    {
        $this->addr = $value;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $value): self
    {
        $this->city = $value;

        return $this;
    }

    public function __construct(array $apiResponse = [])
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->kladr = isset($apiResponse['kladr']) ? (string) $apiResponse['kladr'] : null;
        $this->street = (string) ($apiResponse['street'] ?? null);
        $this->house = (string) ($apiResponse['house'] ?? null);
        $this->apartment = isset($apiResponse['apartment']) ? (string) $apiResponse['apartment'] : null;
        $this->entrance = isset($apiResponse['entrance']) ? (string) $apiResponse['entrance'] : null;
        $this->floor = isset($apiResponse['floor']) ? (string) $apiResponse['floor'] : null;
        $this->addr = isset($apiResponse['addr']) ? (string) $apiResponse['addr'] : null;
        $this->city = isset($apiResponse['city']) ? (string) $apiResponse['city'] : null;
    }

    public function jsonSerialize(): array
    {
        return [
            'KLADR' => $this->kladr,
            'Street' => $this->street,
            'House' => $this->house,
            'Apartment' => $this->apartment,
            'Entrance' => $this->entrance,
            'Floor' => $this->floor,
            'Addr' => $this->addr,
            'City' => $this->city,
        ];
    }
}
