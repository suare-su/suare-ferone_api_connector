<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class Group
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
        $this->id = (int) ($apiResponse['ID'] ?? null);
        $this->iikoid = isset($apiResponse['IIKOID']) ? (string) $apiResponse['IIKOID'] : null;
        $this->parentId = (int) ($apiResponse['ParentID'] ?? null);
        $this->nameRu = (string) ($apiResponse['NameRu'] ?? null);
        $this->nameEn = isset($apiResponse['NameEn']) ? (string) $apiResponse['NameEn'] : null;
        $this->place = (int) ($apiResponse['Place'] ?? null);
        $this->groupModifier = (bool) ($apiResponse['GroupModifier'] ?? null);
        $this->notInPlazius = (bool) ($apiResponse['NotInPlazius'] ?? null);
        $this->visible = (bool) ($apiResponse['Visible'] ?? null);
    }
}
