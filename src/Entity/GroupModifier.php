<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class GroupModifier
{
    /** Id группы группового модификатора */
    private int $groupId;

    /** Минимальное количество модификаторов для выбора */
    private int $min;

    /** Максимальное количество модификаторов для выбора */
    private int $max;

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function __construct(array $apiResponse)
    {
        $this->groupId = (int) ($apiResponse['GroupID'] ?? null);
        $this->min = (int) ($apiResponse['Min'] ?? null);
        $this->max = (int) ($apiResponse['Max'] ?? null);
    }
}
