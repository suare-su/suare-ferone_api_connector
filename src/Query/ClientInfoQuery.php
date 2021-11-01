<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for GetClientInfo method.
 */
class ClientInfoQuery extends AbstractQuery
{
    public const PARAM_CLIENT_ID = 'ClientID';
    public const PARAM_PHONE = 'Phone';

    /**
     * Set ClientID parameter.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setClientId(int $id): self
    {
        return $this->resetParams([self::PARAM_CLIENT_ID => $id]);
    }

    /**
     * Set Phone parameter.
     *
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone(string $phone): self
    {
        return $this->resetParams([self::PARAM_PHONE => $phone]);
    }
}
