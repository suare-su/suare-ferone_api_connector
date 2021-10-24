<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class ShopSelected
{
    public const TYPE_DELIVERY = 'delivery';
    public const TYPE_STORE = 'store';

    /** Id */
    private int $id;

    /** Id города */
    private int $cityId;

    /** Префикс заведения для чеков */
    private string $prefix;

    /** Тип заведения: delivery - доставка, store - магазин */
    private string $type;

    /** Заведение может принимать заказы самовывоза */
    private bool $selfService;

    /** Заведение может принимать заказы доставки */
    private bool $delivery;

    /** Заведение доступно в колл-центре */
    private bool $callCenter;

    /** Название */
    private string $name;

    /** Название города */
    private string $city;

    /** Улица заведения */
    private string $street;

    /** Дом заведения */
    private string $house;

    /** Координаты широты заведения по полному адресу */
    private float $addrLat;

    /** Координаты долготы заведения по полному адресу */
    private float $addrLon;

    /** Id смены */
    private int $shiftId;

    /** Дата и время открытия смены в формате ISO-8601 */
    private string $shiftOpened;

    /** Время открытия и закрытия сегодня */
    private ShopSelectedWorking $working;

    /**
     * @var mixed
     */
    private $sort;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCityId(): int
    {
        return $this->cityId;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getType(): string
    {
        return $this->type;
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

    public function getName(): string
    {
        return $this->name;
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

    public function getAddrLat(): float
    {
        return $this->addrLat;
    }

    public function getAddrLon(): float
    {
        return $this->addrLon;
    }

    public function getShiftId(): int
    {
        return $this->shiftId;
    }

    public function getShiftOpened(): string
    {
        return $this->shiftOpened;
    }

    public function getWorking(): ShopSelectedWorking
    {
        return $this->working;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    public function __construct(array $apiResponse)
    {
        $this->id = (int) ($apiResponse['ID'] ?? null);
        $this->cityId = (int) ($apiResponse['CityID'] ?? null);
        $this->prefix = (string) ($apiResponse['Prefix'] ?? null);
        $this->type = (string) ($apiResponse['Type'] ?? null);
        $this->selfService = (bool) ($apiResponse['SelfService'] ?? null);
        $this->delivery = (bool) ($apiResponse['Delivery'] ?? null);
        $this->callCenter = (bool) ($apiResponse['CallCenter'] ?? null);
        $this->name = (string) ($apiResponse['Name'] ?? null);
        $this->city = (string) ($apiResponse['City'] ?? null);
        $this->street = (string) ($apiResponse['Street'] ?? null);
        $this->house = (string) ($apiResponse['House'] ?? null);
        $this->addrLat = (float) ($apiResponse['AddrLat'] ?? null);
        $this->addrLon = (float) ($apiResponse['AddrLon'] ?? null);
        $this->shiftId = (int) ($apiResponse['ShiftID'] ?? null);
        $this->shiftOpened = (string) ($apiResponse['ShiftOpened'] ?? null);
        $this->working = new ShopSelectedWorking($apiResponse['Working'] ?? []);
        $this->sort = ($apiResponse['Sort'] ?? null);
    }
}
