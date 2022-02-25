<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class OrderFinal implements JsonSerializable
{
    public const TYPE_DELIVERY = 'delivery';
    public const TYPE_SELF = 'self';

    /** Id */
    private int $id;

    /** Id города */
    private int $cityId;

    /** Id клиента */
    private int $clientId;

    /** Имя клиента */
    private string $clientName;

    /** Телефон */
    private string $clientPhone;

    /** Тип заказа: delivery - доставка, self - самовывоз */
    private string $type;

    /** Город */
    private ?string $city;

    /** Улица */
    private ?string $street;

    /** Дом */
    private ?string $house;

    /** Квартира */
    private ?string $apartment;

    /** Подъезд */
    private ?string $entrance;

    /** Этаж */
    private ?string $floor;

    /** Стоимость доставки */
    private int $deliveryPrice;

    /** Скидка со стоимости доставки */
    private float $deliveryDiscount;

    /** Доступное количество бонусных баллов для списания по заказу */
    private float $bonusAvailable;

    /** Сумма заказа без скидок */
    private float $sumWithoutDiscount;

    /** Сумма скидок */
    private float $sumDiscount;

    /** Сумма оплаты бонусами */
    private float $sumBonus;

    /** Сумма к оплате */
    private float $total;

    /** Система лояльности сработала */
    private bool $plaziusStatus;

    /** Ошибка этапа системы лояльности */
    private ?string $plaziusErr;

    /**
     * @var OrderProduct[]
     */
    private array $list;
    private ?ShopSelected $shop;

    /**
     * Список доступных интервалов для заказа на время.
     *
     * @var OrderFinalOnTimeHoursInterval[]
     */
    private array $onTimeHoursInterval;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCityId(): int
    {
        return $this->cityId;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function getClientPhone(): string
    {
        return $this->clientPhone;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getHouse(): ?string
    {
        return $this->house;
    }

    public function getApartment(): ?string
    {
        return $this->apartment;
    }

    public function getEntrance(): ?string
    {
        return $this->entrance;
    }

    public function getFloor(): ?string
    {
        return $this->floor;
    }

    public function getDeliveryPrice(): int
    {
        return $this->deliveryPrice;
    }

    public function getDeliveryDiscount(): float
    {
        return $this->deliveryDiscount;
    }

    public function getBonusAvailable(): float
    {
        return $this->bonusAvailable;
    }

    public function getSumWithoutDiscount(): float
    {
        return $this->sumWithoutDiscount;
    }

    public function getSumDiscount(): float
    {
        return $this->sumDiscount;
    }

    public function getSumBonus(): float
    {
        return $this->sumBonus;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getPlaziusStatus(): bool
    {
        return $this->plaziusStatus;
    }

    public function getPlaziusErr(): ?string
    {
        return $this->plaziusErr;
    }

    /**
     * @return OrderProduct[]
     */
    public function getList(): array
    {
        return $this->list;
    }

    public function getShop(): ?ShopSelected
    {
        return $this->shop;
    }

    /**
     * @return OrderFinalOnTimeHoursInterval[]
     */
    public function getOnTimeHoursInterval(): array
    {
        return $this->onTimeHoursInterval;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (int) ($apiResponse['id'] ?? null);
        $this->cityId = (int) ($apiResponse['cityid'] ?? null);
        $this->clientId = (int) ($apiResponse['clientid'] ?? null);
        $this->clientName = (string) ($apiResponse['clientname'] ?? null);
        $this->clientPhone = (string) ($apiResponse['clientphone'] ?? null);
        $this->type = (string) ($apiResponse['type'] ?? null);
        $this->city = isset($apiResponse['city']) ? (string) $apiResponse['city'] : null;
        $this->street = isset($apiResponse['street']) ? (string) $apiResponse['street'] : null;
        $this->house = isset($apiResponse['house']) ? (string) $apiResponse['house'] : null;
        $this->apartment = isset($apiResponse['apartment']) ? (string) $apiResponse['apartment'] : null;
        $this->entrance = isset($apiResponse['entrance']) ? (string) $apiResponse['entrance'] : null;
        $this->floor = isset($apiResponse['floor']) ? (string) $apiResponse['floor'] : null;
        $this->deliveryPrice = (int) ($apiResponse['deliveryprice'] ?? null);
        $this->deliveryDiscount = (float) ($apiResponse['deliverydiscount'] ?? null);
        $this->bonusAvailable = (float) ($apiResponse['bonusavailable'] ?? null);
        $this->sumWithoutDiscount = (float) ($apiResponse['sumwithoutdiscount'] ?? null);
        $this->sumDiscount = (float) ($apiResponse['sumdiscount'] ?? null);
        $this->sumBonus = (float) ($apiResponse['sumbonus'] ?? null);
        $this->total = (float) ($apiResponse['total'] ?? null);
        $this->plaziusStatus = (bool) ($apiResponse['plaziusstatus'] ?? null);
        $this->plaziusErr = isset($apiResponse['plaziuserr']) ? (string) $apiResponse['plaziuserr'] : null;

        $this->list = [];
        $data = isset($apiResponse['list']) && \is_array($apiResponse['list']) ? $apiResponse['list'] : [];
        $data = array_filter($data, fn ($item): bool => \is_array($item));
        foreach ($data as $tmpItem) {
            $this->list[] = new OrderProduct($tmpItem);
        }
        $this->shop = null;
        if (isset($apiResponse['shop']) && \is_array($apiResponse['shop'])) {
            $this->shop = new ShopSelected($apiResponse['shop']);
        }

        $this->onTimeHoursInterval = [];
        $data = isset($apiResponse['ontimehoursinterval']) && \is_array($apiResponse['ontimehoursinterval']) ? $apiResponse['ontimehoursinterval'] : [];
        $data = array_filter($data, fn ($item): bool => \is_array($item));
        foreach ($data as $tmpItem) {
            $this->onTimeHoursInterval[] = new OrderFinalOnTimeHoursInterval($tmpItem);
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'ID' => $this->id,
            'CityID' => $this->cityId,
            'ClientID' => $this->clientId,
            'ClientName' => $this->clientName,
            'ClientPhone' => $this->clientPhone,
            'Type' => $this->type,
            'City' => $this->city,
            'Street' => $this->street,
            'House' => $this->house,
            'Apartment' => $this->apartment,
            'Entrance' => $this->entrance,
            'Floor' => $this->floor,
            'DeliveryPrice' => $this->deliveryPrice,
            'DeliveryDiscount' => $this->deliveryDiscount,
            'BonusAvailable' => $this->bonusAvailable,
            'SumWithoutDiscount' => $this->sumWithoutDiscount,
            'SumDiscount' => $this->sumDiscount,
            'SumBonus' => $this->sumBonus,
            'Total' => $this->total,
            'PlaziusStatus' => $this->plaziusStatus,
            'PlaziusErr' => $this->plaziusErr,
            'List' => array_map(fn (OrderProduct $item): array => $item->jsonSerialize(), $this->list),
            'Shop' => $this->shop ? $this->shop->jsonSerialize() : null,
            'OnTimeHoursInterval' => array_map(fn (OrderFinalOnTimeHoursInterval $item): array => $item->jsonSerialize(), $this->onTimeHoursInterval),
        ];
    }
}
