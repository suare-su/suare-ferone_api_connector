<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for BonusPayOrder.
 */
class BonusPayOrderQuery extends AbstractQuery
{
    public const PARAM_ORDER_ID = 'OrderID';
    public const PARAM_BONUS = 'Bonus';

    /**
     * Set OrderID parameter.
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
     * Set Bonus parameter.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setBonus(int $value): self
    {
        return $this->add(self::PARAM_BONUS, $value);
    }
}
