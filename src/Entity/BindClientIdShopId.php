<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class BindClientIdShopId implements JsonSerializable
{
    /** Id заказа */
    private int $orderId;

    /** Id клиента */
    private int $clientId;

    /** Id заведения самовывоза */
    private int $shopId;

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $value): self
    {
        $this->orderId = $value;

        return $this;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function setClientId(int $value): self
    {
        $this->clientId = $value;

        return $this;
    }

    public function getShopId(): int
    {
        return $this->shopId;
    }

    public function setShopId(int $value): self
    {
        $this->shopId = $value;

        return $this;
    }

    public function __construct(array $apiResponse = [])
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->orderId = (int) ($apiResponse['orderid'] ?? null);
        $this->clientId = (int) ($apiResponse['clientid'] ?? null);
        $this->shopId = (int) ($apiResponse['shopid'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'OrderID' => $this->orderId,
            'ClientID' => $this->clientId,
            'ShopID' => $this->shopId,
        ];
    }
}
