<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object with limit and offset.
 */
abstract class PageredQuery extends AbstractQuery
{
    public const PARAM_LIMIT = 'Limit';
    public const PARAM_OFFSET = 'Offset';

    /**
     * @return $this
     */
    public function setLimit(int $limit): self
    {
        return $this->add(self::PARAM_LIMIT, $limit);
    }

    /**
     * @return $this
     */
    public function setOffset(int $offset): self
    {
        return $this->add(self::PARAM_OFFSET, $offset);
    }
}
