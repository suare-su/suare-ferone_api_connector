<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class ClientAddrsClient
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
        $this->id = (int) ($apiResponse['ID'] ?? null);
        $this->birth = isset($apiResponse['Birth']) ? (string) $apiResponse['Birth'] : null;
        $this->phone = (string) ($apiResponse['Phone'] ?? null);
        $this->name = (string) ($apiResponse['Name'] ?? null);
    }
}
