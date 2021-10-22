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

    public function setCityId(int $id): self
    {
        return $this->add(self::PARAM_CITY_ID, $id);
    }

    public function setSex(string $sex): self
    {
        return $this->add(self::PARAM_SEX, $sex);
    }

    public function setBirth(string $birth): self
    {
        return $this->add(self::PARAM_BIRTH, $birth);
    }
}
