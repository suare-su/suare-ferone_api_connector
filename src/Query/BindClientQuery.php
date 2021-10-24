<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

use SuareSu\FeroneApiConnector\Entity\BindClientIdAddrInfo;
use SuareSu\FeroneApiConnector\Entity\BindClientIdShopId;
use SuareSu\FeroneApiConnector\Entity\BindClientInfoAddrInfo;
use SuareSu\FeroneApiConnector\Entity\BindClientInfoShopId;

/**
 * Query object for BindClient.
 */
class BindClientQuery extends AbstractQuery
{
    /**
     * Set params from BindClientIdAddrInfo object.
     *
     * @param BindClientIdAddrInfo|BindClientIdShopId|BindClientInfoAddrInfo|BindClientInfoShopId $value
     *
     * @return $this
     */
    public function setBindInfo($value): self
    {
        return $this->resetParams($value->jsonSerialize());
    }
}
