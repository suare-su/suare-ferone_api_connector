<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class OrderFinalOnTimeHoursInterval
{
    /** Дата и время в формате MySQL в часовом поясе города */
    private string $value;

    /** Ярлык для отображения времени */
    private string $label;

    public function getValue(): string
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function __construct(array $apiResponse)
    {
        $this->value = (string) ($apiResponse['value'] ?? null);
        $this->label = (string) ($apiResponse['label'] ?? null);
    }
}
