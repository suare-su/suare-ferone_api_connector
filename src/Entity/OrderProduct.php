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
        $this->productId = (int) ($apiResponse['ProductID'] ?? null);
        $this->name = (string) ($apiResponse['Name'] ?? null);
        $this->price = (float) ($apiResponse['Price'] ?? null);
        $this->amount = (int) ($apiResponse['Amount'] ?? null);
        $this->total = (float) ($apiResponse['Total'] ?? null);
        $this->discount = (float) ($apiResponse['Discount'] ?? null);
    }
}
