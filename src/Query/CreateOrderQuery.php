<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

use SuareSu\FeroneApiConnector\Entity\OrderListItem;

/**
 * Query object for CreateOrder.
 */
class CreateOrderQuery extends AbstractQuery
{
    public const PARAM_CITY_ID = 'CityID';
    public const PARAM_TOTAL = 'Total';
    public const PARAM_SOURCE = 'Source';
    public const PARAM_LIST = 'List';

    /**
     * Set CityID parameter.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setCityId(int $value): self
    {
        return $this->add(self::PARAM_CITY_ID, $value);
    }

    /**
     * Set Total parameter.
     *
     * @param float $value
     *
     * @return $this
     */
    public function setTotal(float $value): self
    {
        return $this->add(self::PARAM_TOTAL, $value);
    }

    /**
     * Set Source parameter.
     *
     * @param array|string $value
     *
     * @return $this
     */
    public function setSource($value): self
    {
        if (\is_array($value)) {
            $value = json_encode($value, \JSON_THROW_ON_ERROR);
        }

        return $this->add(self::PARAM_SOURCE, $value);
    }

    /**
     * Set List parameter.
     *
     * @param OrderListItem[] $value
     *
     * @return $this
     */
    public function setList(array $value): self
    {
        foreach ($value as $item) {
            $this->addListItem($item);
        }

        return $this;
    }

    /**
     * Add item to List parameter.
     *
     * @param OrderListItem $value
     *
     * @return $this
     */
    public function addListItem(OrderListItem $value): self
    {
        $array = $value->jsonSerialize();
        if (empty($array['Mods'])) {
            unset($array['Mods']);
        }

        return $this->addToArray(self::PARAM_LIST, $array);
    }
}
