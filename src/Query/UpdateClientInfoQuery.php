<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for UpdateClientInfo.
 */
class UpdateClientInfoQuery extends AbstractQuery
{
    public const PARAM_CLIENT_ID = 'ClientID';
    public const PARAM_NAME = 'Name';
    public const PARAM_BIRTH = 'Birth';

    /**
     * Set ClientID parameter.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setClientId(int $value): self
    {
        return $this->add(self::PARAM_CLIENT_ID, $value);
    }

    /**
     * Set Name parameter.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setName(string $value): self
    {
        return $this->add(self::PARAM_NAME, $value);
    }

    /**
     * Set Birth parameter.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setBirth(string $value): self
    {
        return $this->add(self::PARAM_BIRTH, $value);
    }
}
