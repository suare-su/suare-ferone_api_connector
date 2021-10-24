<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for GetClientAddrs.
 */
class ClientAddrsQuery extends AbstractQuery
{
    public const PARAM_ORDER_ID = 'OrderID';
    public const PARAM_PHONE = 'Phone';

    /**
     * Set Phone parameter.
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
