<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class ClientInfo implements JsonSerializable
{
    /** Телефон */
    private string $phone;

    /** Имя клиента */
    private string $name;

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $value): self
    {
        $this->phone = $value;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value): self
    {
        $this->name = $value;

        return $this;
    }

    public function __construct(array $apiResponse = [])
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->phone = (string) ($apiResponse['phone'] ?? null);
        $this->name = (string) ($apiResponse['name'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'Phone' => $this->phone,
            'Name' => $this->name,
        ];
    }
}
