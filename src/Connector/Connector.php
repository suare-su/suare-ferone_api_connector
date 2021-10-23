<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Connector;

use DateTimeImmutable;
use SuareSu\FeroneApiConnector\Entity\City;
use SuareSu\FeroneApiConnector\Entity\Client;
use SuareSu\FeroneApiConnector\Entity\FindCitiesResponse;
use SuareSu\FeroneApiConnector\Entity\MenuItem;
use SuareSu\FeroneApiConnector\Entity\Order;
use SuareSu\FeroneApiConnector\Entity\OrderStatus;
use SuareSu\FeroneApiConnector\Entity\Review;
use SuareSu\FeroneApiConnector\Entity\ReviewQuestion;
use SuareSu\FeroneApiConnector\Entity\Shop;
use SuareSu\FeroneApiConnector\Exception\ApiException;
use SuareSu\FeroneApiConnector\Exception\TransportException;
use SuareSu\FeroneApiConnector\Query\ClientBonusQuery;
use SuareSu\FeroneApiConnector\Query\ClientListQuery;
use SuareSu\FeroneApiConnector\Query\ClientOrdersListQuery;
use SuareSu\FeroneApiConnector\Query\ClientReviewsListQuery;
use SuareSu\FeroneApiConnector\Query\MenuQuery;
use SuareSu\FeroneApiConnector\Query\OrdersListQuery;
use SuareSu\FeroneApiConnector\Query\Query;
use SuareSu\FeroneApiConnector\Query\ReviewsListQuery;
use SuareSu\FeroneApiConnector\Transport\Transport;
use SuareSu\FeroneApiConnector\Transport\TransportRequest;
use SuareSu\FeroneApiConnector\Transport\TransportResponse;
use Throwable;

/**
 * Object that represents Ferone API methods.
 */
class Connector
{
    private Transport $transport;

    /**
     * @param Transport $transport
     */
    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * PingAPI method implementation.
     *
     * @return bool
     */
    public function pingApi(): bool
    {
        try {
            $this->sendRequestInternal('PingAPI');
        } catch (ApiException|TransportException $e) {
            return false;
        }

        return true;
    }

    /**
     * GetTokenExpiry method implementation.
     *
     * @return DateTimeImmutable
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getTokenExpiry(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetTokenExpiry')->getData();

        return $this->instantiateDateTimeObject($data['ExpiresOn'] ?? '');
    }

    /**
     * SendSMS method implementation.
     *
     * @param string $phoneNumber
     * @param string $message
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function sendSMS(string $phoneNumber, string $message): void
    {
        $this->sendRequestInternal(
            'SendSMS',
            [
                'Phone' => $phoneNumber,
                'Message' => $message,
            ]
        );
    }

    /**
     * GetCitiesList method implementation.
     *
     * @return City[]
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getCitiesList(): array
    {
        $response = $this->sendRequestInternal('GetCitiesList');

        return array_map(
            fn (array $item): City => new City($item),
            $response->getData()
        );
    }

    /**
     * GetCitiesList method implementation.
     *
     * @param int $id
     *
     * @return City
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getCityInfo(int $id): City
    {
        $response = $this->sendRequestInternal(
            'GetCityInfo',
            [
                'CityID' => $id,
            ]
        );

        return new City($response->getData());
    }

    /**
     * GetCitiesLastChanged method implementation.
     *
     * @return DateTimeImmutable
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getCitiesLastChanged(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetCitiesLastChanged')->getData();

        return $this->instantiateDateTimeObject($data['Changed'] ?? '');
    }

    /**
     * GetShopsList method implementation.
     *
     * @return Shop[]
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getShopsList(): array
    {
        $response = $this->sendRequestInternal('GetShopsList');

        return array_map(
            fn (array $item): Shop => new Shop($item),
            $response->getData()
        );
    }

    /**
     * GetShopInfo method implementation.
     *
     * @param int $id
     *
     * @return Shop
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getShopInfo(int $id): Shop
    {
        $response = $this->sendRequestInternal(
            'GetShopInfo',
            [
                'ShopID' => $id,
            ]
        );

        return new Shop($response->getData());
    }

    /**
     * GetShopsLastChanged method implementation.
     *
     * @return DateTimeImmutable
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getShopsLastChanged(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetShopsLastChanged')->getData();

        return $this->instantiateDateTimeObject($data['Changed'] ?? '');
    }

    /**
     * GetMenu method implementation.
     *
     * @param MenuQuery $query
     *
     * @return MenuItem[]
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getMenu(MenuQuery $query): array
    {
        $response = $this->sendRequestInternal('GetMenu', $query);

        return array_map(
            fn (array $item): MenuItem => new MenuItem($item),
            $response->getData()
        );
    }

    /**
     * GetMenuLastChanged method implementation.
     *
     * @return DateTimeImmutable
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getMenuLastChanged(): DateTimeImmutable
    {
        $data = $this->sendRequestInternal('GetMenuLastChanged')->getData();

        return new DateTimeImmutable($data['Changed'] ?? '');
    }

    /**
     * GetClientsList method implementation.
     *
     * @param ClientListQuery $query
     *
     * @return Client[]
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getClientsList(ClientListQuery $query): array
    {
        $response = $this->sendRequestInternal('GetClientsList', $query);

        return array_map(
            fn (array $item): Client => new Client($item),
            $response->getData()
        );
    }

    /**
     * GetClientInfo method implementation.
     *
     * @param int $id
     *
     * @return Client
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getClientInfo(int $id): Client
    {
        $response = $this->sendRequestInternal(
            'GetClientInfo',
            [
                'ClientID' => $id,
            ]
        );

        return new Client($response->getData());
    }

    /**
     * GetClientBonus method implementation.
     *
     * @param ClientBonusQuery $query
     *
     * @return int
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getClientBonus(ClientBonusQuery $query): int
    {
        $data = $this->sendRequestInternal('GetClientBonus', $query)->getData();

        return (int) ($data['Balance'] ?? 0);
    }

    /**
     * UpdateClientInfo method implementation.
     *
     * @param int         $clientId
     * @param string      $name
     * @param string|null $birth
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function updateClientInfo(int $clientId, string $name, ?string $birth = null): void
    {
        $params = [
            'ClientID' => $clientId,
            'Name' => $name,
        ];
        if ($birth !== null) {
            $params['Birth'] = $birth;
        }

        $this->sendRequestInternal('UpdateClientInfo', $params);
    }

    /**
     * GetOrdersList method implementation.
     *
     * @param OrdersListQuery $query
     *
     * @return Order[]
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getOrdersList(OrdersListQuery $query): array
    {
        $response = $this->sendRequestInternal('GetOrdersList', $query);

        return array_map(
            fn (array $item): Order => new Order($item),
            $response->getData()
        );
    }

    /**
     * GetClientOrdersList method implementation.
     *
     * @param ClientOrdersListQuery $query
     *
     * @return Order[]
     *
     * @throws ApiException
     * @throws TransportException
     *
     * @TODO Source property has string type for this method - issue on API side - so it fails on every call
     */
    public function getClientOrdersList(ClientOrdersListQuery $query): array
    {
        $response = $this->sendRequestInternal('GetClientOrdersList', $query);

        return array_map(
            fn (array $item): Order => new Order($item),
            $response->getData()
        );
    }

    /**
     * GetOrderInfo method implementation.
     *
     * @param int $id
     *
     * @return Order
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getOrderInfo(int $id): Order
    {
        $response = $this->sendRequestInternal(
            'GetOrderInfo',
            [
                'OrderID' => $id,
            ]
        );

        return new Order($response->getData());
    }

    /**
     * GetOrderStatus method implementation.
     *
     * @param int $id
     *
     * @return OrderStatus
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getOrderStatus(int $id): OrderStatus
    {
        $response = $this->sendRequestInternal(
            'GetOrderStatus',
            [
                'OrderID' => $id,
            ]
        );

        return new OrderStatus($response->getData());
    }

    /**
     * GetOrderStatus method implementation.
     *
     * @param int $id
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function deleteOrder(int $id): void
    {
        $this->sendRequestInternal(
            'DeleteOrder',
            [
                'OrderID' => $id,
            ]
        );
    }

    /**
     * GetReviewsList method implementation.
     *
     * @param ReviewsListQuery $query
     *
     * @return Review[]
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getReviewsList(ReviewsListQuery $query): array
    {
        $response = $this->sendRequestInternal('GetReviewsList', $query);

        return array_map(
            fn (array $item): Review => new Review($item),
            $response->getData()
        );
    }

    /**
     * GetReviewsList method implementation.
     *
     * @param ClientReviewsListQuery $query
     *
     * @return Review[]
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getClientReviewsList(ClientReviewsListQuery $query): array
    {
        $response = $this->sendRequestInternal('GetClientReviewsList', $query);

        return array_map(
            fn (array $item): Review => new Review($item),
            $response->getData()
        );
    }

    /**
     * GetReviewInfo method implementation.
     *
     * @param int $id
     *
     * @return Review
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getReviewInfo(int $id): Review
    {
        $response = $this->sendRequestInternal(
            'GetReviewInfo',
            [
                'ReviewID' => $id,
            ]
        );

        return new Review($response->getData());
    }

    /**
     * GetReviewsQuestions method implementation.
     *
     * @param ClientReviewsListQuery $query
     *
     * @return ReviewQuestion[]
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getReviewsQuestions(): array
    {
        $response = $this->sendRequestInternal('GetReviewsQuestions');

        return array_map(
            fn (array $item): ReviewQuestion => new ReviewQuestion($item),
            $response->getData()
        );
    }

    /**
     * AddReview method implementation with rating.
     *
     * @param int         $orderId
     * @param string      $review
     * @param int         $rating
     * @param string|null $photo
     *
     * @return int
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function addReviewRating(int $orderId, string $review, int $rating, ?string $photo = null): int
    {
        $params = [
            'OrderID' => $orderId,
            'Review' => $review,
            'Rating' => $rating,
        ];
        if ($photo !== null) {
            $params['Photo'] = $photo;
        }

        $data = $this->sendRequestInternal('AddReview', $params)->getData();

        return (int) ($data['ID'] ?? 0);
    }

    /**
     * AddReview method implementation with questions.
     *
     * @param int             $orderId
     * @param string          $review
     * @param array<int, int> $questions
     * @param string|null     $photo
     *
     * @return int
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function addReviewQuestions(int $orderId, string $review, array $questions, ?string $photo = null): int
    {
        $params = [
            'OrderID' => $orderId,
            'Review' => $review,
            'Questions' => [],
        ];
        foreach ($questions as $id => $rating) {
            $params['Questions'][] = [
                'ID' => $id,
                'Rating' => $rating,
            ];
        }
        if ($photo !== null) {
            $params['Photo'] = $photo;
        }

        $data = $this->sendRequestInternal('AddReview', $params)->getData();

        return (int) ($data['ID'] ?? 0);
    }

    /**
     * GetStreetAutocompleteStatus method implementation.
     *
     * @return bool
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getStreetAutocompleteStatus(): bool
    {
        $data = $this->sendRequestInternal('GetStreetAutocompleteStatus')->getData();

        return (bool) ($data['Status'] ?? false);
    }

    /**
     * GetOrderOnlineStatus method implementation.
     *
     * @return bool
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getOrderOnlineStatus(): bool
    {
        $data = $this->sendRequestInternal('GetOrderOnlineStatus')->getData();

        return (bool) ($data['Status'] ?? false);
    }

    /**
     * GetOrderOnTimeStatus method implementation.
     *
     * @return bool
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getOrderOnTimeStatus(): bool
    {
        $data = $this->sendRequestInternal('GetOrderOnTimeStatus')->getData();

        return (bool) ($data['Status'] ?? false);
    }

    /**
     * GetPayCardStatus method implementation.
     *
     * @return bool
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getPayCardStatus(): bool
    {
        $data = $this->sendRequestInternal('GetPayCardStatus')->getData();

        return (bool) ($data['Status'] ?? false);
    }

    /**
     * GetPayOnlineStatus method implementation.
     *
     * @return bool
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function getPayOnlineStatus(): bool
    {
        $data = $this->sendRequestInternal('GetPayOnlineStatus')->getData();

        return (bool) ($data['Status'] ?? false);
    }

    /**
     * GetReviewsQuestions method implementation.
     *
     * @param string $city
     *
     * @return FindCitiesResponse[]
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function findCities(string $city): array
    {
        $response = $this->sendRequestInternal('SearchCitiesInKLADR', ['City' => $city]);

        return array_map(
            fn (array $item): FindCitiesResponse => new FindCitiesResponse($item),
            $response->getData()
        );
    }

    /**
     * Create and send request using transport.
     *
     * @param string      $method
     * @param array|Query $params
     *
     * @return TransportResponse
     *
     * @throws ApiException
     * @throws TransportException
     */
    private function sendRequestInternal(string $method, $params = []): TransportResponse
    {
        if ($params instanceof Query) {
            $params = $params->getParams();
        }

        $request = new TransportRequest($method, $params);

        return $this->transport->sendRequest($request);
    }

    /**
     * Instantiate new DateTimeImmutable from string.
     *
     * @param string $dateTime
     *
     * @return DateTimeImmutable
     */
    private function instantiateDateTimeObject(string $dateTime): DateTimeImmutable
    {
        try {
            return new DateTimeImmutable($dateTime);
        } catch (Throwable $e) {
            $message = sprintf('Error while dateTime instantiation: %s', $e->getMessage());
            throw new ApiException($message, 0, $e);
        }
    }
}
