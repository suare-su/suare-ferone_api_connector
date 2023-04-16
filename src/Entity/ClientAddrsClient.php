<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class ClientAddrsClient implements \JsonSerializable
{
    /** Id клиента */
    private int $id;

    /** Месяц и день рождения клиента в формате ММДД */
    private ?string $birth;

    /** Телефон */
    private string $phone;

    /** Имя клиента */
    private string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function getBirth(): ?string
    {
        return $this->birth;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (int) ($apiResponse['id'] ?? null);
        $this->birth = isset($apiResponse['birth']) ? (string) $apiResponse['birth'] : null;
        $this->phone = (string) ($apiResponse['phone'] ?? null);
        $this->name = (string) ($apiResponse['name'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'ID' => $this->id,
            'Birth' => $this->birth,
            'Phone' => $this->phone,
            'Name' => $this->name,
        ];
    }
}
