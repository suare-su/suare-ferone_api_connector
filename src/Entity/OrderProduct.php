<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class OrderProduct
{
    /** Id продукта */
    private int $productId;

    /** Название продукта (вместе с модификаторами через +) */
    private string $name;

    /** Стоимость за еденицу (считается вместе со стоимостью добавленых модификаторов) */
    private float $price;

    /** Количество */
    private int $amount;

    /** Суммарная стоимость позиции */
    private float $total;

    /** Скидка от суммарной стоимости позиции */
    private float $discount;

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->productId = (int) ($apiResponse['productid'] ?? null);
        $this->name = (string) ($apiResponse['name'] ?? null);
        $this->price = (float) ($apiResponse['price'] ?? null);
        $this->amount = (int) ($apiResponse['amount'] ?? null);
        $this->total = (float) ($apiResponse['total'] ?? null);
        $this->discount = (float) ($apiResponse['discount'] ?? null);
    }
}
