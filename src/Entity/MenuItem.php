<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class MenuItem
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

        $this->group = new Group($apiResponse['group'] ?? []);
        $this->products = [];
        foreach (($apiResponse['products'] ?? []) as $tmpItem) {
            $this->products[] = new Product(\is_array($tmpItem) ? $tmpItem : []);
        }
    }
}
