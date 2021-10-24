<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class Client implements JsonSerializable
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
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (int) ($apiResponse['id'] ?? null);
        $this->cityId = (int) ($apiResponse['cityid'] ?? null);
        $this->registred = (string) ($apiResponse['registred'] ?? null);
        $this->birth = (string) ($apiResponse['birth'] ?? null);
        $this->phone = (string) ($apiResponse['phone'] ?? null);
        $this->name = (string) ($apiResponse['name'] ?? null);
        $this->sex = (string) ($apiResponse['sex'] ?? null);
        $this->status = (string) ($apiResponse['status'] ?? null);
        $this->comment = isset($apiResponse['comment']) ? (string) $apiResponse['comment'] : null;
        $this->placedOrders = (int) ($apiResponse['placedorders'] ?? null);
        $this->canceledOrders = (int) ($apiResponse['canceledorders'] ?? null);
        $this->ordersTotalSum = (int) ($apiResponse['orderstotalsum'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'ID' => $this->id,
            'CityID' => $this->cityId,
            'Registred' => $this->registred,
            'Birth' => $this->birth,
            'Phone' => $this->phone,
            'Name' => $this->name,
            'Sex' => $this->sex,
            'Status' => $this->status,
            'Comment' => $this->comment,
            'PlacedOrders' => $this->placedOrders,
            'CanceledOrders' => $this->canceledOrders,
            'OrdersTotalSum' => $this->ordersTotalSum,
        ];
    }
}
