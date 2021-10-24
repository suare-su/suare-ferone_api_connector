<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class BindClientInfoAddrInfo implements JsonSerializable
{
    /** Id заказа */
    private int $orderId;
    private ClientInfo $clientInfo;
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

    public function getClientInfo(): ClientInfo
    {
        return $this->clientInfo;
    }

    public function setClientInfo(ClientInfo $value): self
    {
        $this->clientInfo = $value;

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
        $this->clientInfo = new ClientInfo($apiResponse['clientinfo'] ?? []);
        $this->addrInfo = new AddrInfo($apiResponse['addrinfo'] ?? []);
    }

    public function jsonSerialize(): array
    {
        return [
            'OrderID' => $this->orderId,
            'ClientInfo' => $this->clientInfo->jsonSerialize(),
            'AddrInfo' => $this->addrInfo->jsonSerialize(),
        ];
    }
}
