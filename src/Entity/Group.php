<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

use JsonSerializable;

class Group implements JsonSerializable
{
    /** Id */
    private int $id;

    /** Guid группы в iiko */
    private ?string $iikoid;

    /** Родительская группа */
    private int $parentId;

    /** Название по-русски */
    private string $nameRu;

    /** Название по-английски */
    private ?string $nameEn;

    /** Позиция группы для сортировки */
    private int $place;

    /** Группа является групповым модификатором */
    private bool $groupModifier;

    /** Не учитывается в системе лояльности */
    private bool $notInPlazius;

    /** Группа видима */
    private bool $visible;

    public function getId(): int
    {
        return $this->id;
    }

    public function getIikoid(): ?string
    {
        return $this->iikoid;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function getNameRu(): string
    {
        return $this->nameRu;
    }

    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    public function getPlace(): int
    {
        return $this->place;
    }

    public function getGroupModifier(): bool
    {
        return $this->groupModifier;
    }

    public function getNotInPlazius(): bool
    {
        return $this->notInPlazius;
    }

    public function getVisible(): bool
    {
        return $this->visible;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (int) ($apiResponse['id'] ?? null);
        $this->iikoid = isset($apiResponse['iikoid']) ? (string) $apiResponse['iikoid'] : null;
        $this->parentId = (int) ($apiResponse['parentid'] ?? null);
        $this->nameRu = (string) ($apiResponse['nameru'] ?? null);
        $this->nameEn = isset($apiResponse['nameen']) ? (string) $apiResponse['nameen'] : null;
        $this->place = (int) ($apiResponse['place'] ?? null);
        $this->groupModifier = (bool) ($apiResponse['groupmodifier'] ?? null);
        $this->notInPlazius = (bool) ($apiResponse['notinplazius'] ?? null);
        $this->visible = (bool) ($apiResponse['visible'] ?? null);
    }

    public function jsonSerialize(): array
    {
        return [
            'ID' => $this->id,
            'IIKOID' => $this->iikoid,
            'ParentID' => $this->parentId,
            'NameRu' => $this->nameRu,
            'NameEn' => $this->nameEn,
            'Place' => $this->place,
            'GroupModifier' => $this->groupModifier,
            'NotInPlazius' => $this->notInPlazius,
            'Visible' => $this->visible,
        ];
    }
}
