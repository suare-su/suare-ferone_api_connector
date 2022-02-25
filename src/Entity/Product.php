<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class Product implements JsonSerializable
{
    public const TYPE_PRODUCT = 'product';
    public const TYPE_MODIFIER = 'modifier';
    public const SHOP_TYPE_DELIVERY = 'delivery';
    public const SHOP_TYPE_STORE = 'store';
    public const UNITS_KG = 'кг';
    public const UNITS_L = 'л';
    public const UNITS_SHT = 'шт.';
    public const UNITS_PORTS = 'порц.';

    /** Id */
    private int $id;

    /** Guid продукта в iiko */
    private ?string $iikoid;

    /** Родительская группа */
    private int $groupId;

    /** Тип продукта: product - продукт, modifier - модификатор */
    private string $type;

    /** Тип заведения: delivery - доставка, store - магазин */
    private string $shopType;

    /** Название по-русски */
    private string $nameRu;

    /** Название по-английски */
    private ?string $nameEn;

    /** Описание по-русски */
    private ?string $descriptionRu;

    /** Описание по-английски */
    private ?string $descriptionEn;

    /** Ссылка на картинку */
    private ?string $image;

    /** ККАЛ на 100 гр */
    private ?int $energy;

    /** Углеводы */
    private ?float $carbohydrate;

    /** Жиры */
    private ?float $fat;

    /** Белки */
    private ?float $fiber;

    /** Размер */
    private float $size;

    /** Еденицы измерения для размера */
    private string $units;

    /** Цена города (указывается для главного множителя) */
    private int $price;

    /** Позиция продукта для сортировки */
    private int $place;

    /** Главный множитель, является шагом кол-ва продукта, например, если главный множитель 4, то минимальное количество продукта для отображения 4, а при увеличении в корзине будет прибавляться по 4 - 4, 8, 12, 16 */
    private int $majorMultiplier;

    /** Второстепенный множитель, указывается если необходим неравномерный шаг, например, главный множитель 4, а второстепенный 5, тогда увеличение пойдет следующим образом - 4, 9, 13, 18, 22 */
    private int $minorMultiplier;

    /** Промокод для продукта, при срабатывании продукт добавляется в корзину, можно указывать несколько через точку с запятой */
    private ?string $promoCode;

    /** Название акции для промокода */
    private ?string $promoTitle;

    /** Описание условия срабатывания акции */
    private ?string $promoDesc;

    /** Акция имеет условия */
    private ?bool $promoCondition;

    /** Не учитывается в системе лояльности */
    private bool $notInPlazius;

    /** Продукт доступен */
    private bool $visible;

    /** Продукт в стоп-листе города (показывать можно, заказывать нельзя) */
    private bool $stop;

    /**
     * Продукт в стоп-листе заведений.
     *
     * @var int[]
     */
    private array $stopShops;

    /**
     * Список используемых групповых модификаторов.
     *
     * @var GroupModifier[]
     */
    private array $mods;

    public function getId(): int
    {
        return $this->id;
    }

    public function getIikoid(): ?string
    {
        return $this->iikoid;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getShopType(): string
    {
        return $this->shopType;
    }

    public function getNameRu(): string
    {
        return $this->nameRu;
    }

    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    public function getDescriptionRu(): ?string
    {
        return $this->descriptionRu;
    }

    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getEnergy(): ?int
    {
        return $this->energy;
    }

    public function getCarbohydrate(): ?float
    {
        return $this->carbohydrate;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function getFiber(): ?float
    {
        return $this->fiber;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function getUnits(): string
    {
        return $this->units;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getPlace(): int
    {
        return $this->place;
    }

    public function getMajorMultiplier(): int
    {
        return $this->majorMultiplier;
    }

    public function getMinorMultiplier(): int
    {
        return $this->minorMultiplier;
    }

    public function getPromoCode(): ?string
    {
        return $this->promoCode;
    }

    public function getPromoTitle(): ?string
    {
        return $this->promoTitle;
    }

    public function getPromoDesc(): ?string
    {
        return $this->promoDesc;
    }

    public function getPromoCondition(): ?bool
    {
        return $this->promoCondition;
    }

    public function getNotInPlazius(): bool
    {
        return $this->notInPlazius;
    }

    public function getVisible(): bool
    {
        return $this->visible;
    }

    public function getStop(): bool
    {
        return $this->stop;
    }

    /**
     * @return int[]
     */
    public function getStopShops(): array
    {
        return $this->stopShops;
    }

    /**
     * @return GroupModifier[]
     */
    public function getMods(): array
    {
        return $this->mods;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (int) ($apiResponse['id'] ?? null);
        $this->iikoid = isset($apiResponse['iikoid']) ? (string) $apiResponse['iikoid'] : null;
        $this->groupId = (int) ($apiResponse['groupid'] ?? null);
        $this->type = (string) ($apiResponse['type'] ?? null);
        $this->shopType = (string) ($apiResponse['shoptype'] ?? null);
        $this->nameRu = (string) ($apiResponse['nameru'] ?? null);
        $this->nameEn = isset($apiResponse['nameen']) ? (string) $apiResponse['nameen'] : null;
        $this->descriptionRu = isset($apiResponse['descriptionru']) ? (string) $apiResponse['descriptionru'] : null;
        $this->descriptionEn = isset($apiResponse['descriptionen']) ? (string) $apiResponse['descriptionen'] : null;
        $this->image = isset($apiResponse['image']) ? (string) $apiResponse['image'] : null;
        $this->energy = isset($apiResponse['energy']) ? (int) $apiResponse['energy'] : null;
        $this->carbohydrate = isset($apiResponse['carbohydrate']) ? (float) $apiResponse['carbohydrate'] : null;
        $this->fat = isset($apiResponse['fat']) ? (float) $apiResponse['fat'] : null;
        $this->fiber = isset($apiResponse['fiber']) ? (float) $apiResponse['fiber'] : null;
        $this->size = (float) ($apiResponse['size'] ?? null);
        $this->units = (string) ($apiResponse['units'] ?? null);
        $this->price = (int) ($apiResponse['price'] ?? null);
        $this->place = (int) ($apiResponse['place'] ?? null);
        $this->majorMultiplier = (int) ($apiResponse['majormultiplier'] ?? null);
        $this->minorMultiplier = (int) ($apiResponse['minormultiplier'] ?? null);
        $this->promoCode = isset($apiResponse['promocode']) ? (string) $apiResponse['promocode'] : null;
        $this->promoTitle = isset($apiResponse['promotitle']) ? (string) $apiResponse['promotitle'] : null;
        $this->promoDesc = isset($apiResponse['promodesc']) ? (string) $apiResponse['promodesc'] : null;
        $this->promoCondition = isset($apiResponse['promocondition']) ? (bool) $apiResponse['promocondition'] : null;
        $this->notInPlazius = (bool) ($apiResponse['notinplazius'] ?? null);
        $this->visible = (bool) ($apiResponse['visible'] ?? null);
        $this->stop = (bool) ($apiResponse['stop'] ?? null);

        $this->stopShops = [];
        $data = isset($apiResponse['stopshops']) && \is_array($apiResponse['stopshops']) ? $apiResponse['stopshops'] : [];
        $data = array_filter($data, fn ($item): bool => \is_array($item));
        foreach ($data as $tmpItem) {
            $this->stopShops[] = (int) $tmpItem;
        }

        $this->mods = [];
        $data = isset($apiResponse['mods']) && \is_array($apiResponse['mods']) ? $apiResponse['mods'] : [];
        $data = array_filter($data, fn ($item): bool => \is_array($item));
        foreach ($data as $tmpItem) {
            $this->mods[] = new GroupModifier($tmpItem);
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'ID' => $this->id,
            'IIKOID' => $this->iikoid,
            'GroupID' => $this->groupId,
            'Type' => $this->type,
            'ShopType' => $this->shopType,
            'NameRu' => $this->nameRu,
            'NameEn' => $this->nameEn,
            'DescriptionRu' => $this->descriptionRu,
            'DescriptionEn' => $this->descriptionEn,
            'Image' => $this->image,
            'Energy' => $this->energy,
            'Carbohydrate' => $this->carbohydrate,
            'Fat' => $this->fat,
            'Fiber' => $this->fiber,
            'Size' => $this->size,
            'Units' => $this->units,
            'Price' => $this->price,
            'Place' => $this->place,
            'MajorMultiplier' => $this->majorMultiplier,
            'MinorMultiplier' => $this->minorMultiplier,
            'PromoCode' => $this->promoCode,
            'PromoTitle' => $this->promoTitle,
            'PromoDesc' => $this->promoDesc,
            'PromoCondition' => $this->promoCondition,
            'NotInPlazius' => $this->notInPlazius,
            'Visible' => $this->visible,
            'Stop' => $this->stop,
            'StopShops' => $this->stopShops,
            'mods' => array_map(fn (GroupModifier $item): array => $item->jsonSerialize(), $this->mods),
        ];
    }
}
