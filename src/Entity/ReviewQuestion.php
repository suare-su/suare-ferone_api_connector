<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class ReviewQuestion
{
    /** Id */
    private int $id;

    /** Вопрос по-русски */
    private string $questionRu;

    /** Вопрос по-английски */
    private ?string $questionEn;

    public function getId(): int
    {
        return $this->id;
    }

    public function getQuestionRu(): string
    {
        return $this->questionRu;
    }

    public function getQuestionEn(): ?string
    {
        return $this->questionEn;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (int) ($apiResponse['id'] ?? null);
        $this->questionRu = (string) ($apiResponse['questionru'] ?? null);
        $this->questionEn = isset($apiResponse['questionen']) ? (string) $apiResponse['questionen'] : null;
    }
}
