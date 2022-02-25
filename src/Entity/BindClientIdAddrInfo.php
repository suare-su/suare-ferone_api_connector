<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class BindClientIdAddrInfo implements JsonSerializable
{
    /** Id заказа */
    private int $orderId;

    /** Id клиента */
    private int $clientId;
    private AddrInfo $addrInfo;

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

    public function getAddrInfo(): AddrInfo
    {
        return $this->addrInfo;
    }

    public function setAddrInfo(AddrInfo $value): self
    {
        $this->addrInfo = $value;

        return $this;
    }

    public function __construct(array $apiResponse = [])
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->orderId = (int) ($apiResponse['orderid'] ?? null);
        $this->clientId = (int) ($apiResponse['clientid'] ?? null);
        $this->addrInfo = new AddrInfo((array) ($apiResponse['addrinfo'] ?? []));
    }

    public function jsonSerialize(): array
    {
        return [
            'OrderID' => $this->orderId,
            'ClientID' => $this->clientId,
            'AddrInfo' => $this->addrInfo->jsonSerialize(),
        ];
    }
}
