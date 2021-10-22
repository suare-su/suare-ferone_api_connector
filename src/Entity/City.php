<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class City
{
    /** Id */
    private int $id;

    /** Название */
    private string $name;

    /** Часовой пояс */
    private string $timezone;

    /** Минимальная сумма заказа */
    private int $orderMinimalSum;

    /** Стоимость доставки */
    private int $orderDelivery;

    /** Сумма заказа для бесплатной доставки */
    private int $orderFreeDeliverySum;

    /** Id страны */
    private int $countryId;

    /** Название страны */
    private string $countryName;

    /** Телефонный код страны */
    private string $countryCode;

    /** Телефонный код города (количество цифр в телефонном коде) */
    private string $cityCode;

    /** Если 0, в CityCode указан телефонный код, если 1, то указано количество цифр в телефонном коде */
    private int $cityMultiCode;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function getOrderMinimalSum(): int
    {
        return $this->orderMinimalSum;
    }

    public function getOrderDelivery(): int
    {
        return $this->orderDelivery;
    }

    public function getOrderFreeDeliverySum(): int
    {
        return $this->orderFreeDeliverySum;
    }

    public function getCountryId(): int
    {
        return $this->countryId;
    }

    public function getCountryName(): string
    {
        return $this->countryName;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getCityCode(): string
    {
        return $this->cityCode;
    }

    public function getCityMultiCode(): int
    {
        return $this->cityMultiCode;
    }

    public function __construct(array $apiResponse)
    {
        $this->id = (int) ($apiResponse['ID'] ?? null);
        $this->name = (string) ($apiResponse['Name'] ?? null);
        $this->timezone = (string) ($apiResponse['Timezone'] ?? null);
        $this->orderMinimalSum = (int) ($apiResponse['OrderMinimalSum'] ?? null);
        $this->orderDelivery = (int) ($apiResponse['OrderDelivery'] ?? null);
        $this->orderFreeDeliverySum = (int) ($apiResponse['OrderFreeDeliverySum'] ?? null);
        $this->countryId = (int) ($apiResponse['CountryID'] ?? null);
        $this->countryName = (string) ($apiResponse['CountryName'] ?? null);
        $this->countryCode = (string) ($apiResponse['CountryCode'] ?? null);
        $this->cityCode = (string) ($apiResponse['CityCode'] ?? null);
        $this->cityMultiCode = (int) ($apiResponse['CityMultiCode'] ?? null);
    }
}
