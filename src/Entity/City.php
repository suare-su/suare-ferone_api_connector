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
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (int) ($apiResponse['id'] ?? null);
        $this->name = (string) ($apiResponse['name'] ?? null);
        $this->timezone = (string) ($apiResponse['timezone'] ?? null);
        $this->orderMinimalSum = (int) ($apiResponse['orderminimalsum'] ?? null);
        $this->orderDelivery = (int) ($apiResponse['orderdelivery'] ?? null);
        $this->orderFreeDeliverySum = (int) ($apiResponse['orderfreedeliverysum'] ?? null);
        $this->countryId = (int) ($apiResponse['countryid'] ?? null);
        $this->countryName = (string) ($apiResponse['countryname'] ?? null);
        $this->countryCode = (string) ($apiResponse['countrycode'] ?? null);
        $this->cityCode = (string) ($apiResponse['citycode'] ?? null);
        $this->cityMultiCode = (int) ($apiResponse['citymulticode'] ?? null);
    }
}
