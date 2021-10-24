<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class Shop
{
    public const TYPE_DELIVERY = 'delivery';
    public const TYPE_STORE = 'store';

    /** Id */
    private int $id;

    /** Внутренний код заведения */
    private string $code;

    /** Префикс заведения для чеков */
    private string $prefix;

    /** Тип заведения: delivery - доставка, store - магазин */
    private string $type;

    /** Название */
    private string $name;

    /** Заведение может принимать заказы самовывоза */
    private bool $selfService;

    /** Заведение может принимать заказы доставки */
    private bool $delivery;

    /** Заведение доступно в колл-центре */
    private bool $callCenter;

    /** Телефон заведения */
    private string $phone;

    /** Id города */
    private int $cityId;

    /** Название города */
    private string $city;

    /** Улица заведения */
    private string $street;

    /** Дом заведения */
    private string $house;

    /** Полный адрес заведения до дома */
    private ?string $addr;

    /** Координаты широты заведения по полному адресу */
    private ?float $addrLat;

    /** Координаты долготы заведения по полному адресу */
    private ?float $addrLon;

    /** Json-строка зоны доставки в формате geojson */
    private ?string $deliveryZone;

    /** Заведение активно и может принимать заказы */
    private bool $active;

    /** Время открытия и закрытия по дням недели */
    private ShopWorkingTime $workingTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSelfService(): bool
    {
        return $this->selfService;
    }

    public function getDelivery(): bool
    {
        return $this->delivery;
    }

    public function getCallCenter(): bool
    {
        return $this->callCenter;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getCityId(): int
    {
        return $this->cityId;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getHouse(): string
    {
        return $this->house;
    }

    public function getAddr(): ?string
    {
        return $this->addr;
    }

    public function getAddrLat(): ?float
    {
        return $this->addrLat;
    }

    public function getAddrLon(): ?float
    {
        return $this->addrLon;
    }

    public function getDeliveryZone(): ?string
    {
        return $this->deliveryZone;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function getWorkingTime(): ShopWorkingTime
    {
        return $this->workingTime;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (int) ($apiResponse['id'] ?? null);
        $this->code = (string) ($apiResponse['code'] ?? null);
        $this->prefix = (string) ($apiResponse['prefix'] ?? null);
        $this->type = (string) ($apiResponse['type'] ?? null);
        $this->name = (string) ($apiResponse['name'] ?? null);
        $this->selfService = (bool) ($apiResponse['selfservice'] ?? null);
        $this->delivery = (bool) ($apiResponse['delivery'] ?? null);
        $this->callCenter = (bool) ($apiResponse['callcenter'] ?? null);
        $this->phone = (string) ($apiResponse['phone'] ?? null);
        $this->cityId = (int) ($apiResponse['cityid'] ?? null);
        $this->city = (string) ($apiResponse['city'] ?? null);
        $this->street = (string) ($apiResponse['street'] ?? null);
        $this->house = (string) ($apiResponse['house'] ?? null);
        $this->addr = isset($apiResponse['addr']) ? (string) $apiResponse['addr'] : null;
        $this->addrLat = isset($apiResponse['addrlat']) ? (float) $apiResponse['addrlat'] : null;
        $this->addrLon = isset($apiResponse['addrlon']) ? (float) $apiResponse['addrlon'] : null;
        $this->deliveryZone = isset($apiResponse['deliveryzone']) ? (string) $apiResponse['deliveryzone'] : null;
        $this->active = (bool) ($apiResponse['active'] ?? null);
        $this->workingTime = new ShopWorkingTime($apiResponse['workingtime'] ?? []);
    }
}
