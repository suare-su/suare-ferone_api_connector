<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Query;

/**
 * Query object for getOrdersList.
 */
class OrdersListQuery extends PageredQuery
{
    public const PARAM_PERIOD = 'Period';
    public const PARAM_CITIES_IDS = 'CitiesIDs';
    public const PARAM_SHOP_IDS = 'ShopsIDs';
    public const PARAM_ORDER_TYPES = 'OrdersTypes';
    public const PARAM_CALLBACK = 'Callback';
    public const PARAM_PLAZIUS_OFF = 'PlaziusOff';
    public const PARAM_NOT_IN_IIKO = 'NotInIIKO';
    public const PARAM_NOT_IN_PBI = 'NotInPBI';
    public const PARAM_HIDDEN_MENU = 'HiddenMenu';
    public const PARAM_EXTERNAL = 'External';

    /**
     * Set Period parameter.
     *
     * @param \DateTimeInterface $from
     * @param \DateTimeInterface $to
     *
     * @return $this
     */
    public function setPeriod(\DateTimeInterface $from, \DateTimeInterface $to): self
    {
        $this->add(
            self::PARAM_PERIOD,
            [
                'From' => $from->format('d.m.Y'),
                'To' => $to->format('d.m.Y'),
            ]
        );

        return $this;
    }

    /**
     * Set CitiesIDs parameter.
     *
     * @param int[] $value
     *
     * @return $this
     */
    public function setCitiesIDs(array $value): self
    {
        $this->add(self::PARAM_CITIES_IDS, $value);

        return $this;
    }

    /**
     * Set ShopsIDs parameter.
     *
     * @param int[] $value
     *
     * @return $this
     */
    public function setShopsIDs(array $value): self
    {
        $this->add(self::PARAM_SHOP_IDS, $value);

        return $this;
    }

    /**
     * Set Callback parameter.
     *
     * @param string[] $value
     *
     * @return $this
     */
    public function setOrdersTypes(array $value): self
    {
        $this->add(self::PARAM_ORDER_TYPES, $value);

        return $this;
    }

    /**
     * Set Callback parameter.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setCallback(bool $value): self
    {
        $this->add(self::PARAM_CALLBACK, $value);

        return $this;
    }

    /**
     * Set PlaziusOff parameter.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setPlaziusOff(bool $value): self
    {
        $this->add(self::PARAM_PLAZIUS_OFF, $value);

        return $this;
    }

    /**
     * Set NotInIIKO parameter.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setNotInIIKO(bool $value): self
    {
        $this->add(self::PARAM_NOT_IN_IIKO, $value);

        return $this;
    }

    /**
     * Set NotInPBI parameter.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setNotInPBI(bool $value): self
    {
        $this->add(self::PARAM_NOT_IN_PBI, $value);

        return $this;
    }

    /**
     * Set HiddenMenu parameter.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setHiddenMenu(bool $value): self
    {
        $this->add(self::PARAM_HIDDEN_MENU, $value);

        return $this;
    }

    /**
     * Set External parameter.
     *
     * @param bool $value
     *
     * @return $this
     */
    public function setExternal(bool $value): self
    {
        $this->add(self::PARAM_EXTERNAL, $value);

        return $this;
    }
}
