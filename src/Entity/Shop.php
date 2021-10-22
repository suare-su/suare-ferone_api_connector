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
        $this->id = (int) ($apiResponse['ID'] ?? null);
        $this->code = (string) ($apiResponse['Code'] ?? null);
        $this->prefix = (string) ($apiResponse['Prefix'] ?? null);
        $this->type = (string) ($apiResponse['Type'] ?? null);
        $this->name = (string) ($apiResponse['Name'] ?? null);
        $this->selfService = (bool) ($apiResponse['SelfService'] ?? null);
        $this->delivery = (bool) ($apiResponse['Delivery'] ?? null);
        $this->callCenter = (bool) ($apiResponse['CallCenter'] ?? null);
        $this->phone = (string) ($apiResponse['Phone'] ?? null);
        $this->cityId = (int) ($apiResponse['CityID'] ?? null);
        $this->city = (string) ($apiResponse['City'] ?? null);
        $this->street = (string) ($apiResponse['Street'] ?? null);
        $this->house = (string) ($apiResponse['House'] ?? null);
        $this->addr = isset($apiResponse['Addr']) ? (string) $apiResponse['Addr'] : null;
        $this->addrLat = isset($apiResponse['AddrLat']) ? (float) $apiResponse['AddrLat'] : null;
        $this->addrLon = isset($apiResponse['AddrLon']) ? (float) $apiResponse['AddrLon'] : null;
        $this->deliveryZone = isset($apiResponse['DeliveryZone']) ? (string) $apiResponse['DeliveryZone'] : null;
        $this->active = (bool) ($apiResponse['Active'] ?? null);
        $this->workingTime = new ShopWorkingTime($apiResponse['WorkingTime'] ?? []);
    }
}
