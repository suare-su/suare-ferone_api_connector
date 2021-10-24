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
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->groupId = (int) ($apiResponse['groupid'] ?? null);
        $this->min = (int) ($apiResponse['min'] ?? null);
        $this->max = (int) ($apiResponse['max'] ?? null);
    }
}
