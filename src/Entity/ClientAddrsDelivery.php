<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class ClientAddrsDelivery
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
        $this->status = (bool) ($apiResponse['Status'] ?? null);
        $this->price = (int) ($apiResponse['Price'] ?? null);
        $this->minimal = (int) ($apiResponse['Minimal'] ?? null);
        $this->total = (int) ($apiResponse['Total'] ?? null);
        $this->totalWithDelivery = (int) ($apiResponse['TotalWithDelivery'] ?? null);
    }
}
