<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class OrderListItemMod implements \JsonSerializable
{
    /** Id продукта */
    private int $id;

    /** Id группового модификатора */
    private int $groupId;

    /** Стоимость за еденицу модификатора */
    private float $price;

    /** Количество модификаторов */
    private int $amount;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): self
    {
        $this->id = $value;

        return $this;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function setGroupId(int $value): self
    {
        $this->groupId = $value;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $value): self
    {
        $this->price = $value;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $value): self
    {
        $this->amount = $value;

        return $this;
    }

    public function __construct(array $apiResponse = [])
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (int) ($apiResponse['id'] ?? null);
        $this->groupId = (int) ($apiResponse['groupid'] ?? null);
        $this->price = (float) ($apiResponse['price'] ?? null);
        $this->amount = (int) ($apiResponse['amount'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'ID' => $this->id,
            'GroupID' => $this->groupId,
            'Price' => $this->price,
            'Amount' => $this->amount,
        ];
    }
}
