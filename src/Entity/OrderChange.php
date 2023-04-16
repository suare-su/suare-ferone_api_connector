<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class OrderChange implements \JsonSerializable
{
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_PROCESS = 'process';
    public const STATUS_DONE = 'done';
    public const STATUS_PACKED = 'packed';
    public const STATUS_DELIVERY = 'delivery';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_EDIT = 'edit';
    public const STATUS_FUCKEDUP = 'fuckedup';
    public const STATUS_SHIFT = 'shift';

    /** Статус изменения: accepted - принят, process - производство, done - готов, packed - собран, delivery - доставляется, delivered - доставлен, closed - закрыт (выполнен), canceled - отменен, edit - заказ изменен, fuckedup - отправлена смс с извинениями, shift - смена заведения */
    private string $status;

    /** Дата и время изменения в формате ISO-8601 */
    private string $changed;

    /** Имя и фамилия пользователя ответственного за изменение */
    private ?string $user;

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getChanged(): string
    {
        return $this->changed;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->status = (string) ($apiResponse['status'] ?? null);
        $this->changed = (string) ($apiResponse['changed'] ?? null);
        $this->user = isset($apiResponse['user']) ? (string) $apiResponse['user'] : null;
    }

    public function jsonSerialize(): array
    {
        return [
            'Status' => $this->status,
            'Changed' => $this->changed,
            'User' => $this->user,
        ];
    }
}
