<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class BindClientInfoShopId implements JsonSerializable
{
    /** Id заказа */
    private int $orderId;
    private ClientInfo $clientInfo;

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

    public function getClientInfo(): ClientInfo
    {
        return $this->clientInfo;
    }

    public function setClientInfo(ClientInfo $value): self
    {
        $this->clientInfo = $value;

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
        $this->clientInfo = new ClientInfo(\is_array($apiResponse['clientinfo']) ? $apiResponse['clientinfo'] : []);
        $this->shopId = (int) ($apiResponse['shopid'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'OrderID' => $this->orderId,
            'ClientInfo' => $this->clientInfo->jsonSerialize(),
            'ShopID' => $this->shopId,
        ];
    }
}
