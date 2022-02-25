<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for CheckAddressInCityZone.
 */
class CheckAddressInCityZoneQuery extends AbstractQuery
{
    public const PARAM_CITY_ID = 'CityID';
    public const PARAM_ADDRESS = 'Address';

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
