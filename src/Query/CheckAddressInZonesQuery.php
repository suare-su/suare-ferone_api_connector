<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for CheckAddressInZones.
 */
class CheckAddressInZonesQuery extends AbstractQuery
{
    public const PARAM_ORDER_ID = 'OrderID';
    public const PARAM_ADDRESS = 'Address';

    /**
     * Set OrderID parameter.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setOrderId(int $value): self
    {
        return $this->add(self::PARAM_ORDER_ID, $value);
    }

    /**
     * Set Address parameter.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setAddress(string $value): self
    {
        return $this->add(self::PARAM_ADDRESS, $value);
    }
}
