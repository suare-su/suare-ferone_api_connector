<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class ShopWorkingTime implements \JsonSerializable
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
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->monOpen = (string) ($apiResponse['monopen'] ?? null);
        $this->monClose = (string) ($apiResponse['monclose'] ?? null);
        $this->tueOpen = (string) ($apiResponse['tueopen'] ?? null);
        $this->tueClose = (string) ($apiResponse['tueclose'] ?? null);
        $this->wedOpen = (string) ($apiResponse['wedopen'] ?? null);
        $this->wedClose = (string) ($apiResponse['wedclose'] ?? null);
        $this->thuOpen = (string) ($apiResponse['thuopen'] ?? null);
        $this->thuClose = (string) ($apiResponse['thuclose'] ?? null);
        $this->friOpen = (string) ($apiResponse['friopen'] ?? null);
        $this->friClose = (string) ($apiResponse['friclose'] ?? null);
        $this->satOpen = (string) ($apiResponse['satopen'] ?? null);
        $this->satClose = (string) ($apiResponse['satclose'] ?? null);
        $this->sunOpen = (string) ($apiResponse['sunopen'] ?? null);
        $this->sunClose = (string) ($apiResponse['sunclose'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'MonOpen' => $this->monOpen,
            'MonClose' => $this->monClose,
            'TueOpen' => $this->tueOpen,
            'TueClose' => $this->tueClose,
            'WedOpen' => $this->wedOpen,
            'WedClose' => $this->wedClose,
            'ThuOpen' => $this->thuOpen,
            'ThuClose' => $this->thuClose,
            'FriOpen' => $this->friOpen,
            'FriClose' => $this->friClose,
            'SatOpen' => $this->satOpen,
            'SatClose' => $this->satClose,
            'SunOpen' => $this->sunOpen,
            'SunClose' => $this->sunClose,
        ];
    }
}
