<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class OrderStatus implements \JsonSerializable
{
    public const STATUS_NEW = 'new';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_PROCESS = 'process';
    public const STATUS_DONE = 'done';
    public const STATUS_PACKED = 'packed';
    public const STATUS_DELIVERY = 'delivery';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_CANCELED = 'canceled';
    public const TYPE_DELIVERY = 'delivery';
    public const TYPE_SELF = 'self';

    /** Статус заказа: new - новый, accepted - принят, process - производство, done - готов, packed - собран, delivery - доставляется, delivered - доставлен, closed - закрыт (выполнен), canceled - отменен */
    private string $status;

    /** Тип заказа: delivery - доставка, self - самовывоз */
    private string $type;

    /** Дата и время изменения в формате ISO-8601 */
    private string $changed;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getChanged(): string
    {
        return $this->changed;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->status = (string) ($apiResponse['status'] ?? null);
        $this->type = (string) ($apiResponse['type'] ?? null);
        $this->changed = (string) ($apiResponse['changed'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'Status' => $this->status,
            'Type' => $this->type,
            'Changed' => $this->changed,
        ];
    }
}
