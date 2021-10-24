<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class OrderSourceType
{
    private string $type;

    /** URL источника перехода, если type = Сайт */
    private ?string $referer;
    private ?string $utmSource;
    private ?string $utmMedium;
    private ?string $utmCampaign;
    private ?string $utmTerm;
    private ?string $utmContent;

    public function getType(): string
    {
        return $this->type;
    }

    public function getReferer(): ?string
    {
        return $this->referer;
    }

    public function getUtmSource(): ?string
    {
        return $this->utmSource;
    }

    public function getUtmMedium(): ?string
    {
        return $this->utmMedium;
    }

    public function getUtmCampaign(): ?string
    {
        return $this->utmCampaign;
    }

    public function getUtmTerm(): ?string
    {
        return $this->utmTerm;
    }

    public function getUtmContent(): ?string
    {
        return $this->utmContent;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->type = (string) ($apiResponse['type'] ?? null);
        $this->referer = isset($apiResponse['referer']) ? (string) $apiResponse['referer'] : null;
        $this->utmSource = isset($apiResponse['utm_source']) ? (string) $apiResponse['utm_source'] : null;
        $this->utmMedium = isset($apiResponse['utm_medium']) ? (string) $apiResponse['utm_medium'] : null;
        $this->utmCampaign = isset($apiResponse['utm_campaign']) ? (string) $apiResponse['utm_campaign'] : null;
        $this->utmTerm = isset($apiResponse['utm_term']) ? (string) $apiResponse['utm_term'] : null;
        $this->utmContent = isset($apiResponse['utm_content']) ? (string) $apiResponse['utm_content'] : null;
    }
}
