<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Interface for object that represents params for method in connector.
 */
interface Query
{
    public function getParams(): array;
}
