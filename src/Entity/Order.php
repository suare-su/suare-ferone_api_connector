<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class Order
{
    public const SHOP_TYPE_DELIVERY = 'delivery';
    public const SHOP_TYPE_STORE = 'store';
    public const TYPE_DELIVERY = 'delivery';
    public const TYPE_SELF = 'self';
    public const PAY_TYPE_CASH = 'cash';
    public const PAY_TYPE_CARD = 'card';
    public const PAY_TYPE_EXTERNAL = 'external';
    public const STATUS_NEW = 'new';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_PROCESS = 'process';
    public const STATUS_DONE = 'done';
    public const STATUS_PACKED = 'packed';
    public const STATUS_DELIVERY = 'delivery';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_CANCELED = 'canceled';

    /** Id */
    private int $id;

    /** Id города */
    private int $cityId;

    /** Id заведения */
    private int $shopId;

    /** Id смены */
    private int $shiftId;

    /** Id клиента */
    private int $clientId;

    /** Имя клиента */
    private string $clientName;

    /** Телефон */
    private string $clientPhone;

    /** Название заведения */
    private string $shopName;

    /** Префикс заведения для чеков */
    private string $shopPrefix;

    /** Тип заведения: delivery - доставка, store - магазин */
    private string $shopType;

    /** Порядковый номер чека в смене (полный номер чека ShopPrefix-CheckSN) */
    private int $checkSn;

    /** Дата и время открытия смены в формате ISO-8601 */
    private string $shiftOpened;

    /** Тип заказа: delivery - доставка, self - самовывоз */
    private string $type;

    /** Текущее время сервера в формате ISO-8601 */
    private string $nowTime;

    /** Дата и время создания заказа в формате ISO-8601 */
    private string $created;

    /** Дата и время выполнения заказа в формате ISO-8601 */
    private ?string $completed;

    /** Дата и время выполнения заказа по плану в формате ISO-8601 */
    private string $completedPlan;

    /** Разница между плановым временем выполнения заказа и временем выполнения заказа в минутах, показывает насколько быстрее/медленее плана был выполнен заказ */
    private ?int $expiresPlanIn;

    /** Имя и фамилия оператора принявшего заказ */
    private ?string $operator;

    /** Имя и фамилия курьера назначенного на заказ */
    private ?string $courier;

    /** Город */
    private string $city;

    /** Улица */
    private string $street;

    /** Дом */
    private string $house;

    /** Квартира */
    private ?string $apartment;

    /** Подъезд */
    private ?string $entrance;

    /** Этаж */
    private ?string $floor;

    /** Полный адрес заказа до дома */
    private ?string $addr;

    /** Координаты широты заказа по полному адресу */
    private ?float $addrLat;

    /** Координаты долготы заказа по полному адресу */
    private ?float $addrLon;

    /** Точность опеределния координат, если 0 - координаты точны до дома, если больше - координаты не точные (до улицы, до города) */
    private ?int $addrAcc;

    /** Стоимость доставки */
    private int $deliveryPrice;

    /** Скидка со стоимости доставки */
    private float $deliveryDiscount;

    /** Координаты долготы курьера по завершении доставки */
    private ?float $deliveryLon;

    /** Координаты широты курьера по завершении доставки */
    private ?float $deliveryLat;

    /** Точность опеределния координат, если 0 - координаты точны до дома, если больше - координаты не точные (до улицы, до города) */
    private ?int $deliveryAcc;

    /** Тип оплаты: cash - наличные, card - банковская карта, external - внешняя оплата (для сайта - онлайн оплата) */
    private string $payType;

    /** Сдача с суммы, указывается при PayType = cash */
    private ?int $cashChange;

    /** Доступное количество бонусных баллов для списания по заказу */
    private float $bonusAvailable;

    /** Количество начисленных бонусных баллов по заказу */
    private ?float $bonusCredited;

    /** Сумма заказа без скидок */
    private float $sumWithoutDiscount;

    /** Сумма скидок */
    private float $sumDiscount;

    /** Сумма оплаты бонусами */
    private float $sumBonus;

    /** Сумма к оплате */
    private float $total;

    /** Заказ на время */
    private bool $onTime;

    /** Внешний заказ (создан сайтом или другой внешней системой) */
    private bool $createdBySite;

    /** Заказ отправлен в iiko */
    private bool $iikoStatus;

    /** Заказ отправлен в power bi */
    private bool $pbiStatus;

    /** Система лояльности сработала */
    private bool $plaziusStatus;

    /** Ошибка этапа системы лояльности */
    private ?string $plaziusErr;

    /** Клиентом запрошен обратный звонок */
    private bool $callback;

    /** В заказе присутствует продукты из скрытого меню */
    private bool $hiddenMenu;

    /** По заказу была отправлена смс с извинениями за опоздание */
    private bool $fuckedUp;

    /** Комментарий клиента или оператора (видим клиенту) */
    private ?string $comment;
    private ?OrderSourceType $source;

    /** Статус заказа: new - новый, accepted - принят, process - производство, done - готов, packed - собран, delivery - доставляется, delivered - доставлен, closed - закрыт (выполнен), canceled - отменен */
    private string $status;

    /** Причина отмены заказа */
    private ?string $cancelReason;

    /**
     * @var OrderProduct[]
     */
    private array $list;

    /**
     * @var OrderChange[]
     */
    private array $changes;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCityId(): int
    {
        return $this->cityId;
    }

    public function getShopId(): int
    {
        return $this->shopId;
    }

    public function getShiftId(): int
    {
        return $this->shiftId;
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

    public function getShopName(): string
    {
        return $this->shopName;
    }

    public function getShopPrefix(): string
    {
        return $this->shopPrefix;
    }

    public function getShopType(): string
    {
        return $this->shopType;
    }

    public function getCheckSn(): int
    {
        return $this->checkSn;
    }

    public function getShiftOpened(): string
    {
        return $this->shiftOpened;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getNowTime(): string
    {
        return $this->nowTime;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function getCompleted(): ?string
    {
        return $this->completed;
    }

    public function getCompletedPlan(): string
    {
        return $this->completedPlan;
    }

    public function getExpiresPlanIn(): ?int
    {
        return $this->expiresPlanIn;
    }

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    public function getCourier(): ?string
    {
        return $this->courier;
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

    public function getAddrAcc(): ?int
    {
        return $this->addrAcc;
    }

    public function getDeliveryPrice(): int
    {
        return $this->deliveryPrice;
    }

    public function getDeliveryDiscount(): float
    {
        return $this->deliveryDiscount;
    }

    public function getDeliveryLon(): ?float
    {
        return $this->deliveryLon;
    }

    public function getDeliveryLat(): ?float
    {
        return $this->deliveryLat;
    }

    public function getDeliveryAcc(): ?int
    {
        return $this->deliveryAcc;
    }

    public function getPayType(): string
    {
        return $this->payType;
    }

    public function getCashChange(): ?int
    {
        return $this->cashChange;
    }

    public function getBonusAvailable(): float
    {
        return $this->bonusAvailable;
    }

    public function getBonusCredited(): ?float
    {
        return $this->bonusCredited;
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

    public function getOnTime(): bool
    {
        return $this->onTime;
    }

    public function getCreatedBySite(): bool
    {
        return $this->createdBySite;
    }

    public function getIikoStatus(): bool
    {
        return $this->iikoStatus;
    }

    public function getPbiStatus(): bool
    {
        return $this->pbiStatus;
    }

    public function getPlaziusStatus(): bool
    {
        return $this->plaziusStatus;
    }

    public function getPlaziusErr(): ?string
    {
        return $this->plaziusErr;
    }

    public function getCallback(): bool
    {
        return $this->callback;
    }

    public function getHiddenMenu(): bool
    {
        return $this->hiddenMenu;
    }

    public function getFuckedUp(): bool
    {
        return $this->fuckedUp;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getSource(): ?OrderSourceType
    {
        return $this->source;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    /**
     * @return OrderProduct[]
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     * @return OrderChange[]
     */
    public function getChanges(): array
    {
        return $this->changes;
    }

    public function __construct(array $apiResponse)
    {
        $this->id = (int) ($apiResponse['ID'] ?? null);
        $this->cityId = (int) ($apiResponse['CityID'] ?? null);
        $this->shopId = (int) ($apiResponse['ShopID'] ?? null);
        $this->shiftId = (int) ($apiResponse['ShiftID'] ?? null);
        $this->clientId = (int) ($apiResponse['ClientID'] ?? null);
        $this->clientName = (string) ($apiResponse['ClientName'] ?? null);
        $this->clientPhone = (string) ($apiResponse['ClientPhone'] ?? null);
        $this->shopName = (string) ($apiResponse['ShopName'] ?? null);
        $this->shopPrefix = (string) ($apiResponse['ShopPrefix'] ?? null);
        $this->shopType = (string) ($apiResponse['ShopType'] ?? null);
        $this->checkSn = (int) ($apiResponse['CheckSN'] ?? null);
        $this->shiftOpened = (string) ($apiResponse['ShiftOpened'] ?? null);
        $this->type = (string) ($apiResponse['Type'] ?? null);
        $this->nowTime = (string) ($apiResponse['NowTime'] ?? null);
        $this->created = (string) ($apiResponse['Created'] ?? null);
        $this->completed = isset($apiResponse['Completed']) ? (string) $apiResponse['Completed'] : null;
        $this->completedPlan = (string) ($apiResponse['CompletedPlan'] ?? null);
        $this->expiresPlanIn = isset($apiResponse['ExpiresPlanIn']) ? (int) $apiResponse['ExpiresPlanIn'] : null;
        $this->operator = isset($apiResponse['Operator']) ? (string) $apiResponse['Operator'] : null;
        $this->courier = isset($apiResponse['Courier']) ? (string) $apiResponse['Courier'] : null;
        $this->city = (string) ($apiResponse['City'] ?? null);
        $this->street = (string) ($apiResponse['Street'] ?? null);
        $this->house = (string) ($apiResponse['House'] ?? null);
        $this->apartment = isset($apiResponse['Apartment']) ? (string) $apiResponse['Apartment'] : null;
        $this->entrance = isset($apiResponse['Entrance']) ? (string) $apiResponse['Entrance'] : null;
        $this->floor = isset($apiResponse['Floor']) ? (string) $apiResponse['Floor'] : null;
        $this->addr = isset($apiResponse['Addr']) ? (string) $apiResponse['Addr'] : null;
        $this->addrLat = isset($apiResponse['AddrLat']) ? (float) $apiResponse['AddrLat'] : null;
        $this->addrLon = isset($apiResponse['AddrLon']) ? (float) $apiResponse['AddrLon'] : null;
        $this->addrAcc = isset($apiResponse['AddrAcc']) ? (int) $apiResponse['AddrAcc'] : null;
        $this->deliveryPrice = (int) ($apiResponse['DeliveryPrice'] ?? null);
        $this->deliveryDiscount = (float) ($apiResponse['DeliveryDiscount'] ?? null);
        $this->deliveryLon = isset($apiResponse['DeliveryLon']) ? (float) $apiResponse['DeliveryLon'] : null;
        $this->deliveryLat = isset($apiResponse['DeliveryLat']) ? (float) $apiResponse['DeliveryLat'] : null;
        $this->deliveryAcc = isset($apiResponse['DeliveryAcc']) ? (int) $apiResponse['DeliveryAcc'] : null;
        $this->payType = (string) ($apiResponse['PayType'] ?? null);
        $this->cashChange = isset($apiResponse['CashChange']) ? (int) $apiResponse['CashChange'] : null;
        $this->bonusAvailable = (float) ($apiResponse['BonusAvailable'] ?? null);
        $this->bonusCredited = isset($apiResponse['BonusCredited']) ? (float) $apiResponse['BonusCredited'] : null;
        $this->sumWithoutDiscount = (float) ($apiResponse['SumWithoutDiscount'] ?? null);
        $this->sumDiscount = (float) ($apiResponse['SumDiscount'] ?? null);
        $this->sumBonus = (float) ($apiResponse['SumBonus'] ?? null);
        $this->total = (float) ($apiResponse['Total'] ?? null);
        $this->onTime = (bool) ($apiResponse['OnTime'] ?? null);
        $this->createdBySite = (bool) ($apiResponse['CreatedBySite'] ?? null);
        $this->iikoStatus = (bool) ($apiResponse['IIKOStatus'] ?? null);
        $this->pbiStatus = (bool) ($apiResponse['PBIStatus'] ?? null);
        $this->plaziusStatus = (bool) ($apiResponse['PlaziusStatus'] ?? null);
        $this->plaziusErr = isset($apiResponse['PlaziusErr']) ? (string) $apiResponse['PlaziusErr'] : null;
        $this->callback = (bool) ($apiResponse['Callback'] ?? null);
        $this->hiddenMenu = (bool) ($apiResponse['HiddenMenu'] ?? null);
        $this->fuckedUp = (bool) ($apiResponse['FuckedUp'] ?? null);
        $this->comment = isset($apiResponse['Comment']) ? (string) $apiResponse['Comment'] : null;
        $this->source = isset($apiResponse['Source']) ? new OrderSourceType($apiResponse['Source']) : null;
        $this->status = (string) ($apiResponse['Status'] ?? null);
        $this->cancelReason = isset($apiResponse['CancelReason']) ? (string) $apiResponse['CancelReason'] : null;
        $this->list = [];
        foreach (($apiResponse['List'] ?? []) as $tmpItem) {
            $this->list[] = new OrderProduct(\is_array($tmpItem) ? $tmpItem : []);
        }
        $this->changes = [];
        foreach (($apiResponse['Changes'] ?? []) as $tmpItem) {
            $this->changes[] = new OrderChange(\is_array($tmpItem) ? $tmpItem : []);
        }
    }
}
