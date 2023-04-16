<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class ShopSelectedWorking implements \JsonSerializable
{
    private string $open;
    private string $close;

    public function getOpen(): string
    {
        return $this->open;
    }

    public function getClose(): string
    {
        return $this->close;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->open = (string) ($apiResponse['open'] ?? null);
        $this->close = (string) ($apiResponse['close'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'Open' => $this->open,
            'Close' => $this->close,
        ];
    }
}
