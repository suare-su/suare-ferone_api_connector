<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class FindStreetsResponse implements \JsonSerializable
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
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (string) ($apiResponse['id'] ?? null);
        $this->city = (string) ($apiResponse['city'] ?? null);
        $this->label = (string) ($apiResponse['label'] ?? null);
        $this->value = (string) ($apiResponse['value'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'city' => $this->city,
            'label' => $this->label,
            'value' => $this->value,
        ];
    }
}
