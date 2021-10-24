<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class BindedClient implements JsonSerializable
{
    private int $orderId;
    private int $clientId;

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->orderId = (int) ($apiResponse['orderid'] ?? null);
        $this->clientId = (int) ($apiResponse['clientid'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'OrderID' => $this->orderId,
            'ClientID' => $this->clientId,
        ];
    }
}
