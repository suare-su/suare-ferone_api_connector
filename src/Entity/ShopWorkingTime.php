<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class ShopWorkingTime
{
    private string $monOpen;
    private string $monClose;
    private string $tueOpen;
    private string $tueClose;
    private string $wedOpen;
    private string $wedClose;
    private string $thuOpen;
    private string $thuClose;
    private string $friOpen;
    private string $friClose;
    private string $satOpen;
    private string $satClose;
    private string $sunOpen;
    private string $sunClose;

    public function getMonOpen(): string
    {
        return $this->monOpen;
    }

    public function getMonClose(): string
    {
        return $this->monClose;
    }

    public function getTueOpen(): string
    {
        return $this->tueOpen;
    }

    public function getTueClose(): string
    {
        return $this->tueClose;
    }

    public function getWedOpen(): string
    {
        return $this->wedOpen;
    }

    public function getWedClose(): string
    {
        return $this->wedClose;
    }

    public function getThuOpen(): string
    {
        return $this->thuOpen;
    }

    public function getThuClose(): string
    {
        return $this->thuClose;
    }

    public function getFriOpen(): string
    {
        return $this->friOpen;
    }

    public function getFriClose(): string
    {
        return $this->friClose;
    }

    public function getSatOpen(): string
    {
        return $this->satOpen;
    }

    public function getSatClose(): string
    {
        return $this->satClose;
    }

    public function getSunOpen(): string
    {
        return $this->sunOpen;
    }

    public function getSunClose(): string
    {
        return $this->sunClose;
    }

    public function __construct(array $apiResponse)
    {
        $this->monOpen = (string) ($apiResponse['MonOpen'] ?? null);
        $this->monClose = (string) ($apiResponse['MonClose'] ?? null);
        $this->tueOpen = (string) ($apiResponse['TueOpen'] ?? null);
        $this->tueClose = (string) ($apiResponse['TueClose'] ?? null);
        $this->wedOpen = (string) ($apiResponse['WedOpen'] ?? null);
        $this->wedClose = (string) ($apiResponse['WedClose'] ?? null);
        $this->thuOpen = (string) ($apiResponse['ThuOpen'] ?? null);
        $this->thuClose = (string) ($apiResponse['ThuClose'] ?? null);
        $this->friOpen = (string) ($apiResponse['FriOpen'] ?? null);
        $this->friClose = (string) ($apiResponse['FriClose'] ?? null);
        $this->satOpen = (string) ($apiResponse['SatOpen'] ?? null);
        $this->satClose = (string) ($apiResponse['SatClose'] ?? null);
        $this->sunOpen = (string) ($apiResponse['SunOpen'] ?? null);
        $this->sunClose = (string) ($apiResponse['SunClose'] ?? null);
    }
}
