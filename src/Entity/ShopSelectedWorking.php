<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class ShopSelectedWorking
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
        $this->open = (string) ($apiResponse['Open'] ?? null);
        $this->close = (string) ($apiResponse['Close'] ?? null);
    }
}
