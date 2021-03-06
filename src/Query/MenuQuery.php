<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for GetMenu.
 */
class MenuQuery extends AbstractQuery
{
    public const PARAM_CITY_ID = 'CityID';
    public const PARAM_ONLY_VISIBLE = 'OnlyVisible';

    /**
     * Set CityID parameter.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setCityId(int $id): self
    {
        return $this->add(self::PARAM_CITY_ID, $id);
    }

    /**
     * Set OnlyVisible parameter.
     *
     * @param bool $onlyVisible
     *
     * @return $this
     */
    public function setOnlyVisible(bool $onlyVisible): self
    {
        return $this->add(self::PARAM_ONLY_VISIBLE, $onlyVisible);
    }
}
