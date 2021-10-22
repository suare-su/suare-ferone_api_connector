<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class Client
{
    public const SEX_UNDEFINED = 'undefined';
    public const SEX_MALE = 'male';
    public const SEX_FEMALE = 'female';
    public const STATUS_NORMAL = 'normal';
    public const STATUS_VIP = 'vip';
    public const STATUS_BLACKLIST = 'blacklist';

    /** Id */
    private int $id;

    /** Id города */
    private int $cityId;

    /** Дата и время регистрации в формате ISO-8601 */
    private string $registred;

    /** Месяц и день рождения клиента в формате ММДД */
    private string $birth;

    /** Телефон */
    private string $phone;

    /** Имя клиента */
    private string $name;

    /** Пол клиента: undefined - не указан, male - мужчина, female - женщина */
    private string $sex;

    /** Статус клиента: normal - обычный, vip - важный, blacklist - в черном списке (заказы не оформляются) */
    private string $status;

    /** Системный комментарий, обычно указывается причина попадания в черный список (не для публикации) */
    private ?string $comment;

    /** Кол-во заказов */
    private int $placedOrders;

    /** Кол-во отмененных заказов */
    private int $canceledOrders;

    /** Общая сумма всех размещенных заказов в рублях */
    private int $ordersTotalSum;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCityId(): int
    {
        return $this->cityId;
    }

    public function getRegistred(): string
    {
        return $this->registred;
    }

    public function getBirth(): string
    {
        return $this->birth;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getPlacedOrders(): int
    {
        return $this->placedOrders;
    }

    public function getCanceledOrders(): int
    {
        return $this->canceledOrders;
    }

    public function getOrdersTotalSum(): int
    {
        return $this->ordersTotalSum;
    }

    public function __construct(array $apiResponse)
    {
        $this->id = (int) ($apiResponse['ID'] ?? null);
        $this->cityId = (int) ($apiResponse['CityID'] ?? null);
        $this->registred = (string) ($apiResponse['Registred'] ?? null);
        $this->birth = (string) ($apiResponse['Birth'] ?? null);
        $this->phone = (string) ($apiResponse['Phone'] ?? null);
        $this->name = (string) ($apiResponse['Name'] ?? null);
        $this->sex = (string) ($apiResponse['Sex'] ?? null);
        $this->status = (string) ($apiResponse['Status'] ?? null);
        $this->comment = isset($apiResponse['Comment']) ? (string) $apiResponse['Comment'] : null;
        $this->placedOrders = (int) ($apiResponse['PlacedOrders'] ?? null);
        $this->canceledOrders = (int) ($apiResponse['CanceledOrders'] ?? null);
        $this->ordersTotalSum = (int) ($apiResponse['OrdersTotalSum'] ?? null);
    }
}
