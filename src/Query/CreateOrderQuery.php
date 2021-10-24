<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for CreateOrder.
 */
class CreateOrderQuery extends AbstractQuery
{
    public const PARAM_CITY_ID = 'CityID';
    public const PARAM_TOTAL = 'Total';
    public const PARAM_SOURCE = 'Source';

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
}
