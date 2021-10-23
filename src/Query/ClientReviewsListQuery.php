<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for GetClientReviewsList.
 */
class ClientReviewsListQuery extends PageredQuery
{
    public const PARAM_CLIENT_ID = 'ClientID';

    /**
     * Set ClientID parameter.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setClientId(int $id): self
    {
        return $this->add(self::PARAM_CLIENT_ID, $id);
    }
}
