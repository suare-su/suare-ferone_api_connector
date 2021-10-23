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
        $this->id = (int) ($apiResponse['ID'] ?? null);
        $this->questionRu = (string) ($apiResponse['QuestionRu'] ?? null);
        $this->questionEn = isset($apiResponse['QuestionEn']) ? (string) $apiResponse['QuestionEn'] : null;
    }
}
