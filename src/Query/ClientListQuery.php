<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for GetClientListMethod.
 */
class ClientListQuery extends PageredQuery
{
    public const PARAM_CITY_ID = 'CityID';
    public const PARAM_SEX = 'Sex';
    public const PARAM_BIRTH = 'Birth';

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
     * Set Sex parameter.
     *
     * @param string $sex
     *
     * @return $this
     */
    public function setSex(string $sex): self
    {
        return $this->add(self::PARAM_SEX, $sex);
    }

    /**
     * Set Birth parameter.
     *
     * @param string $birth
     *
     * @return $this
     */
    public function setBirth(string $birth): self
    {
        return $this->add(self::PARAM_BIRTH, $birth);
    }
}
