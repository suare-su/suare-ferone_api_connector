<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Entity;

class Review
{
    /** Id */
    private int $id;

    /** Id города */
    private int $cityId;

    /** Id заведения */
    private int $shopId;

    /** Id заказа */
    private int $orderId;

    /** Id клиента */
    private int $clientId;

    /** Имя клиента */
    private string $clientName;

    /** Телефон */
    private string $clientPhone;

    /** Дата и время создания в формате ISO-8601 */
    private string $created;

    /** Имя и фамилия сотрудника создавшего отзыв */
    private ?string $createdBy;

    /** Отзыв */
    private string $review;

    /** Фотография приложенная к отзыву */
    private ?string $photo;

    /** Общий рейтинг отзыва */
    private int $rating;

    /** Отчет о проверке */
    private ?string $report;

    /** Имя и фамилия сотрудника написавшего отчет */
    private ?string $reportBy;

    /** Дата и время проверки (закрытия) отзыва в формате ISO-8601 */
    private ?string $closed;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCityId(): int
    {
        return $this->cityId;
    }

    public function getShopId(): int
    {
        return $this->shopId;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function getClientPhone(): string
    {
        return $this->clientPhone;
    }

    public function getCreated(): string
    {
        return $this->created;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function getReview(): string
    {
        return $this->review;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getReport(): ?string
    {
        return $this->report;
    }

    public function getReportBy(): ?string
    {
        return $this->reportBy;
    }

    public function getClosed(): ?string
    {
        return $this->closed;
    }

    public function __construct(array $apiResponse)
    {
        $apiResponse = array_change_key_case($apiResponse, \CASE_LOWER);

        $this->id = (int) ($apiResponse['id'] ?? null);
        $this->cityId = (int) ($apiResponse['cityid'] ?? null);
        $this->shopId = (int) ($apiResponse['shopid'] ?? null);
        $this->orderId = (int) ($apiResponse['orderid'] ?? null);
        $this->clientId = (int) ($apiResponse['clientid'] ?? null);
        $this->clientName = (string) ($apiResponse['clientname'] ?? null);
        $this->clientPhone = (string) ($apiResponse['clientphone'] ?? null);
        $this->created = (string) ($apiResponse['created'] ?? null);
        $this->createdBy = isset($apiResponse['createdby']) ? (string) $apiResponse['createdby'] : null;
        $this->review = (string) ($apiResponse['review'] ?? null);
        $this->photo = isset($apiResponse['photo']) ? (string) $apiResponse['photo'] : null;
        $this->rating = (int) ($apiResponse['rating'] ?? null);
        $this->report = isset($apiResponse['report']) ? (string) $apiResponse['report'] : null;
        $this->reportBy = isset($apiResponse['reportby']) ? (string) $apiResponse['reportby'] : null;
        $this->closed = isset($apiResponse['closed']) ? (string) $apiResponse['closed'] : null;
    }
}
