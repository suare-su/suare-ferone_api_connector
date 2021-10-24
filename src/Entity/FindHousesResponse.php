<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class FindHousesResponse
{
    private string $id;
    private string $addr;
    private string $label;
    private string $value;

    public function getId(): string
    {
        return $this->id;
    }

    public function getAddr(): string
    {
        return $this->addr;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (string) ($apiResponse['id'] ?? null);
        $this->addr = (string) ($apiResponse['addr'] ?? null);
        $this->label = (string) ($apiResponse['label'] ?? null);
        $this->value = (string) ($apiResponse['value'] ?? null);
    }
}
