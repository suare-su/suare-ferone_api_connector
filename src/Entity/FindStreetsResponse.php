<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class FindStreetsResponse
{
    private string $id;
    private string $city;
    private string $label;
    private string $value;

    public function getId(): string
    {
        return $this->id;
    }

    public function getCity(): string
    {
        return $this->city;
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
        $this->id = (string) ($apiResponse['id'] ?? null);
        $this->city = (string) ($apiResponse['city'] ?? null);
        $this->label = (string) ($apiResponse['label'] ?? null);
        $this->value = (string) ($apiResponse['value'] ?? null);
    }
}
