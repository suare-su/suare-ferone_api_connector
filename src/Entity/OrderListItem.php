<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class OrderListItem implements JsonSerializable
{
    /** Id продукта */
    private int $id;

    /** Название продукта (вместе с модификаторами через +) */
    private string $name;

    /** Стоимость за еденицу (считается вместе со стоимостью добавленых модификаторов) */
    private float $price;

    /** Количество */
    private int $amount;

    /** Суммарная стоимость позиции */
    private float $total;

    /**
     * @var OrderListItemMod[]
     */
    private array $mods;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): self
    {
        $this->id = $value;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value): self
    {
        $this->name = $value;

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

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $value): self
    {
        $this->total = $value;

        return $this;
    }

    /**
     * @return OrderListItemMod[]
     */
    public function getMods(): array
    {
        return $this->mods;
    }

    /**
     * @param OrderListItemMod[] $value
     */
    public function setMods(array $value): self
    {
        $this->mods = $value;

        return $this;
    }

    public function __construct(array $apiResponse = [])
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (int) ($apiResponse['id'] ?? null);
        $this->name = (string) ($apiResponse['name'] ?? null);
        $this->price = (float) ($apiResponse['price'] ?? null);
        $this->amount = (int) ($apiResponse['amount'] ?? null);
        $this->total = (float) ($apiResponse['total'] ?? null);
        $this->mods = [];
        foreach (($apiResponse['mods'] ?? []) as $tmpItem) {
            $this->mods[] = new OrderListItemMod(\is_array($tmpItem) ? $tmpItem : []);
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
            'Price' => $this->price,
            'Amount' => $this->amount,
            'Total' => $this->total,
            'Mods' => array_map(fn (OrderListItemMod $item): array => $item->jsonSerialize(), $this->mods),
        ];
    }
}
