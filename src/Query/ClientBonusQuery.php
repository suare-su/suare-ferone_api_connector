<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for GetClientListMethod.
 */
class ClientBonusQuery extends AbstractQuery
{
    public const PARAM_PHONE = 'Phone';

    /**
     * Set Phone parameter.
     *
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone(string $phone): self
    {
        return $this->add(self::PARAM_PHONE, $phone);
    }
}
