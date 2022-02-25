<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class MenuItem implements JsonSerializable
{
    private Group $group;

    /**
     * Список продуктов элемента.
     *
     * @var Product[]
     */
    private array $products;

    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->group = new Group((array) ($apiResponse['group'] ?? []));

        $this->products = [];
        $data = isset($apiResponse['products']) && \is_array($apiResponse['products']) ? $apiResponse['products'] : [];
        $data = array_filter($data, fn ($item): bool => \is_array($item));
        foreach ($data as $tmpItem) {
            $this->products[] = new Product($tmpItem);
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'Group' => $this->group->jsonSerialize(),
            'Products' => array_map(fn (Product $item): array => $item->jsonSerialize(), $this->products),
        ];
    }
}
