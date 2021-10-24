<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

use DateTimeInterface;

/**
 * Query object for AcceptOrder.
 */
class AcceptOrderQuery extends AbstractQuery
{
    public const PARAM_ORDER_ID = 'OrderID';
    public const PARAM_PAY = 'Pay';
    public const PARAM_CONFIRMATION = 'Confirmation';
    public const PARAM_CASH_CHANGE = 'CashChange';
    public const PARAM_ON_TIME = 'OnTime';
    public const PARAM_COMMENT = 'Comment';

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
     * Set Pay parameter.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setPay(string $value): self
    {
        return $this->add(self::PARAM_PAY, $value);
    }

    /**
     * Set Confirmation parameter.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setConfirm(bool $value): self
    {
        return $this->add(self::PARAM_CONFIRMATION, $value);
    }

    /**
     * Set CashChange parameter.
     *
     * @param int $value
     *
     * @return $this
     */
    public function setCashChange(int $value): self
    {
        return $this->add(self::PARAM_CASH_CHANGE, $value);
    }

    /**
     * Set OnTime parameter.
     *
     * @param DateTimeInterface $value
     *
     * @return $this
     */
    public function setOnTime(DateTimeInterface $value): self
    {
        return $this->add(self::PARAM_ON_TIME, $value->format('H:i'));
    }

    /**
     * Set Comment parameter.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setComment(string $value): self
    {
        return $this->add(self::PARAM_COMMENT, $value);
    }
}
