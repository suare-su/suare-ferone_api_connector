<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class ClientAddrsDelivery implements JsonSerializable
{
    /** Возможность доставки */
    private bool $status;

    /** Стоимость доставки для заказа */
    private int $price;

    /** Минимальная сумма заказа для доставки */
    private int $minimal;

    /** Сумма заказа */
    private int $total;

    /** Сумма заказа с доставкой */
    private int $totalWithDelivery;

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getMinimal(): int
    {
        return $this->minimal;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getTotalWithDelivery(): int
    {
        return $this->totalWithDelivery;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->status = (bool) ($apiResponse['status'] ?? null);
        $this->price = (int) ($apiResponse['price'] ?? null);
        $this->minimal = (int) ($apiResponse['minimal'] ?? null);
        $this->total = (int) ($apiResponse['total'] ?? null);
        $this->totalWithDelivery = (int) ($apiResponse['totalwithdelivery'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'Status' => $this->status,
            'Price' => $this->price,
            'Minimal' => $this->minimal,
            'Total' => $this->total,
            'TotalWithDelivery' => $this->totalWithDelivery,
        ];
    }
}
