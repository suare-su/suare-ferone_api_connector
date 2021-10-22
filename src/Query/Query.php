<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Interface for object that represents params for method in connector.
 */
interface Query
{
    /**
     * Returns list of params for current query.
     *
     * @return array
     */
    public function getParams(): array;
}
