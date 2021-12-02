<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Connector;

use DateTimeImmutable;
use SuareSu\FeroneApiConnector\Connector\Connector;
use SuareSu\FeroneApiConnector\Entity\BindClientIdShopId;
use SuareSu\FeroneApiConnector\Entity\OrderListItem;
use SuareSu\FeroneApiConnector\Exception\ApiException;
use SuareSu\FeroneApiConnector\Query\AcceptOrderQuery;
use SuareSu\FeroneApiConnector\Query\AddReviewQuestionsQuery;
use SuareSu\FeroneApiConnector\Query\AddReviewRatingQuery;
use SuareSu\FeroneApiConnector\Query\BaseShopQuery;
use SuareSu\FeroneApiConnector\Query\BindClientQuery;
use SuareSu\FeroneApiConnector\Query\BonusPayOrderQuery;
use SuareSu\FeroneApiConnector\Query\CheckAddressInZonesQuery;
use SuareSu\FeroneApiConnector\Query\ClientAddrsQuery;
use SuareSu\FeroneApiConnector\Query\ClientBonusQuery;
use SuareSu\FeroneApiConnector\Query\ClientInfoQuery;
use SuareSu\FeroneApiConnector\Query\ClientListQuery;
use SuareSu\FeroneApiConnector\Query\ClientOrdersListQuery;
use SuareSu\FeroneApiConnector\Query\ClientReviewsListQuery;
use SuareSu\FeroneApiConnector\Query\CreateOrderQuery;
use SuareSu\FeroneApiConnector\Query\MenuQuery;
use SuareSu\FeroneApiConnector\Query\OrdersListQuery;
use SuareSu\FeroneApiConnector\Query\ReviewsListQuery;
use SuareSu\FeroneApiConnector\Query\SendSmsQuery;
use SuareSu\FeroneApiConnector\Query\UpdateClientInfoQuery;
use SuareSu\FeroneApiConnector\Tests\BaseTestCase;
use SuareSu\FeroneApiConnector\Transport\Transport;
use SuareSu\FeroneApiConnector\Transport\TransportRequest;
use SuareSu\FeroneApiConnector\Transport\TransportResponse;
use Throwable;

/**
 * @internal
 */
class ConnectorTest extends BaseTestCase
{
    /**
     * @test
     */
    public function testPingApi(): void
    {
        $transport = $this->createTransportMock('PingAPI');

        $connector = new Connector($transport);

        $this->assertTrue($connector->pingApi());
    }

    /**
     * @test
     */
    public function testDoesNotPingApi(): void
    {
        $transport = $this->createTransportMock(
            'PingAPI',
            [],
            new ApiException()
        );

        $connector = new Connector($transport);

        $this->assertFalse($connector->pingApi());
    }

    /**
     * @test
     */
    public function testGetTokenExpiry(): void
    {
        $date = '2010-10-10 10:10:10';
        $transport = $this->createTransportMock(
            'GetTokenExpiry',
            [],
            [
                'ExpiresOn' => $date,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame(
            $date,
            $connector->getTokenExpiry()->format('Y-m-d H:i:s')
        );
    }

    /**
     * @test
     */
    public function testGetTokenExpiryException(): void
    {
        $date = 'test';
        $transport = $this->createTransportMock(
            'GetTokenExpiry',
            [],
            [
                'ExpiresOn' => $date,
            ]
        );

        $connector = new Connector($transport);

        $this->expectException(ApiException::class);
        $connector->getTokenExpiry();
    }

    /**
     * @test
     */
    public function testSendSMS(): void
    {
        $phoneNumber = '79999999999';
        $message = 'test';
        $query = SendSmsQuery::new()->setPhone($phoneNumber)->setMessage($message);
        $transport = $this->createTransportMock(
            'SendSMS',
            [
                'Phone' => $phoneNumber,
                'Message' => $message,
            ]
        );

        $connector = new Connector($transport);
        $connector->sendSMS($query);
    }

    /**
     * @test
     */
    public function testGetCitiesList(): void
    {
        $id = 123;
        $id1 = 321;
        $transport = $this->createTransportMock(
            'GetCitiesList',
            [],
            [
                [
                    'ID' => $id,
                ],
                [
                    'ID' => $id1,
                ],
            ]
        );

        $connector = new Connector($transport);
        $cities = $connector->getCitiesList();

        $this->assertCount(2, $cities);
        $this->assertSame($id, $cities[0]->getId());
        $this->assertSame($id1, $cities[1]->getId());
    }

    /**
     * @test
     */
    public function testGetCityInfo(): void
    {
        $id = 123;
        $transport = $this->createTransportMock(
            'GetCityInfo',
            [
                'CityID' => $id,
            ],
            [
                'ID' => $id,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame($id, $connector->getCityInfo($id)->getId());
    }

    /**
     * @test
     */
    public function testGetCitiesLastChanged(): void
    {
        $date = '2010-10-10 10:10:10';
        $transport = $this->createTransportMock(
            'GetCitiesLastChanged',
            [],
            [
                'Changed' => $date,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame(
            $date,
            $connector->getCitiesLastChanged()->format('Y-m-d H:i:s')
        );
    }

    /**
     * @test
     */
    public function testGetShopsList(): void
    {
        $id = 123;
        $id1 = 321;
        $transport = $this->createTransportMock(
            'GetShopsList',
            [],
            [
                [
                    'ID' => $id,
                ],
                [
                    'ID' => $id1,
                ],
            ]
        );

        $connector = new Connector($transport);
        $shops = $connector->getShopsList();

        $this->assertCount(2, $shops);
        $this->assertSame($id, $shops[0]->getId());
        $this->assertSame($id1, $shops[1]->getId());
    }

    /**
     * @test
     */
    public function testGetShopInfo(): void
    {
        $id = 123;
        $transport = $this->createTransportMock(
            'GetShopInfo',
            [
                'ShopID' => $id,
            ],
            [
                'ID' => $id,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame($id, $connector->getShopInfo($id)->getId());
    }

    /**
     * @test
     */
    public function testGetShopsLastChanged(): void
    {
        $date = '2010-10-10 10:10:10';
        $transport = $this->createTransportMock(
            'GetShopsLastChanged',
            [],
            [
                'Changed' => $date,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame(
            $date,
            $connector->getShopsLastChanged()->format('Y-m-d H:i:s')
        );
    }

    /**
     * @test
     */
    public function testGetMenu(): void
    {
        $cityId = 333;
        $onlyVisible = false;
        $query = MenuQuery::new()->setCityId($cityId)->setOnlyVisible($onlyVisible);
        $id = 123;
        $id1 = 321;
        $transport = $this->createTransportMock(
            'GetMenu',
            [
                'CityID' => $cityId,
                'OnlyVisible' => $onlyVisible,
            ],
            [
                [
                    'Group' => [
                        'ID' => $id,
                    ],
                ],
                [
                    'Group' => [
                        'ID' => $id1,
                    ],
                ],
            ]
        );

        $connector = new Connector($transport);
        $menuItems = $connector->getMenu($query);

        $this->assertCount(2, $menuItems);
        $this->assertSame($id, $menuItems[0]->getGroup()->getId());
        $this->assertSame($id1, $menuItems[1]->getGroup()->getId());
    }

    /**
     * @test
     */
    public function testGetMenuLastChanged(): void
    {
        $date = '2010-10-10 10:10:10';
        $transport = $this->createTransportMock(
            'GetMenuLastChanged',
            [],
            [
                'Changed' => $date,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame(
            $date,
            $connector->getMenuLastChanged()->format('Y-m-d H:i:s')
        );
    }

    /**
     * @test
     */
    public function testGetClientsList(): void
    {
        $cityId = 333;
        $sex = 'male';
        $birth = '0404';
        $limit = 1;
        $offset = 2;
        $query = ClientListQuery::new()
            ->setCityId($cityId)
            ->setSex($sex)
            ->setBirth($birth)
            ->setLimit($limit)
            ->setOffset($offset);
        $id = 123;
        $id1 = 321;
        $transport = $this->createTransportMock(
            'GetClientsList',
            [
                'CityID' => $cityId,
                'Sex' => $sex,
                'Birth' => $birth,
                'Limit' => $limit,
                'Offset' => $offset,
            ],
            [
                [
                    'ID' => $id,
                ],
                [
                    'ID' => $id1,
                ],
            ]
        );

        $connector = new Connector($transport);
        $clients = $connector->getClientsList($query);

        $this->assertCount(2, $clients);
        $this->assertSame($id, $clients[0]->getId());
        $this->assertSame($id1, $clients[1]->getId());
    }

    /**
     * @test
     */
    public function testGetClientInfoId(): void
    {
        $id = 123;
        $query = ClientInfoQuery::new()->setClientId($id);
        $transport = $this->createTransportMock(
            'GetClientInfo',
            [
                'ClientID' => $id,
            ],
            [
                'ID' => $id,
            ]
        );

        $connector = new Connector($transport);
        $client = $connector->getClientInfo($query);

        $this->assertNotNull($client);
        $this->assertSame($id, $client->getId());
    }

    /**
     * @test
     */
    public function testGetClientInfoPhone(): void
    {
        $phone = '79999999999';
        $id = 123;
        $query = ClientInfoQuery::new()->setPhone($phone);
        $transport = $this->createTransportMock(
            'GetClientInfo',
            [
                'Phone' => $phone,
            ],
            [
                'ID' => $id,
            ]
        );

        $connector = new Connector($transport);
        $client = $connector->getClientInfo($query);

        $this->assertNotNull($client);
        $this->assertSame($id, $client->getId());
    }

    /**
     * @test
     */
    public function testGetClientInfoNotFound(): void
    {
        $id = 123;
        $query = ClientInfoQuery::new()->setClientId($id);
        $transport = $this->createTransportMock(
            'GetClientInfo',
            [
                'ClientID' => $id,
            ],
            new ApiException('test', 30)
        );

        $connector = new Connector($transport);

        $this->assertNull($connector->getClientInfo($query));
    }

    /**
     * @test
     */
    public function testGetClientBonus(): void
    {
        $phone = '79999999999';
        $query = ClientBonusQuery::new()->setPhone($phone);
        $balance = 234;
        $transport = $this->createTransportMock(
            'GetClientBonus',
            [
                'Phone' => $phone,
            ],
            [
                'Balance' => $balance,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame(
            $balance,
            $connector->getClientBonus($query)
        );
    }

    /**
     * @test
     */
    public function testUpdateClientInfo(): void
    {
        $clientId = 234;
        $name = 'test';
        $birth = '0404';
        $query = UpdateClientInfoQuery::new()->setClientId($clientId)->setName($name)->setBirth($birth);
        $transport = $this->createTransportMock(
            'UpdateClientInfo',
            [
                'ClientID' => $clientId,
                'Name' => $name,
                'Birth' => $birth,
            ],
            []
        );

        $connector = new Connector($transport);
        $connector->updateClientInfo($query);
    }

    /**
     * @test
     */
    public function testGetOrdersList(): void
    {
        $from = '10.10.2020';
        $to = '10.10.2021';
        $cities = [333, 444];
        $types = ['delivery'];
        $shops = [555, 666];
        $callback = true;
        $plaziusOff = true;
        $notInIIKO = false;
        $notInPBI = true;
        $hiddenMenu = false;
        $external = true;
        $query = OrdersListQuery::new()
            ->setPeriod(new DateTimeImmutable($from), new DateTimeImmutable($to))
            ->setCitiesIDs($cities)
            ->setOrdersTypes($types)
            ->setShopsIDs($shops)
            ->setCallback($callback)
            ->setPlaziusOff($plaziusOff)
            ->setNotInIIKO($notInIIKO)
            ->setNotInPBI($notInPBI)
            ->setHiddenMenu($hiddenMenu)
            ->setExternal($external);
        $id = 123;
        $id1 = 321;
        $transport = $this->createTransportMock(
            'GetOrdersList',
            [
                'Period' => ['From' => $from, 'To' => $to],
                'CitiesIDs' => $cities,
                'OrdersTypes' => $types,
                'ShopsIDs' => $shops,
                'Callback' => $callback,
                'PlaziusOff' => $plaziusOff,
                'NotInIIKO' => $notInIIKO,
                'NotInPBI' => $notInPBI,
                'HiddenMenu' => $hiddenMenu,
                'External' => $external,
            ],
            [
                [
                    'ID' => $id,
                ],
                [
                    'ID' => $id1,
                ],
            ]
        );

        $connector = new Connector($transport);
        $orders = $connector->getOrdersList($query);

        $this->assertCount(2, $orders);
        $this->assertSame($id, $orders[0]->getId());
        $this->assertSame($id1, $orders[1]->getId());
    }

    /**
     * @test
     */
    public function testGetClientOrdersList(): void
    {
        $clientId = 333;
        $query = ClientOrdersListQuery::new()->setClientId($clientId);
        $id = 123;
        $id1 = 321;
        $transport = $this->createTransportMock(
            'GetClientOrdersList',
            [
                'ClientID' => $clientId,
            ],
            [
                [
                    'ID' => $id,
                ],
                [
                    'ID' => $id1,
                ],
            ]
        );

        $connector = new Connector($transport);
        $orders = $connector->getClientOrdersList($query);

        $this->assertCount(2, $orders);
        $this->assertSame($id, $orders[0]->getId());
        $this->assertSame($id1, $orders[1]->getId());
    }

    /**
     * @test
     */
    public function testGetOrderInfo(): void
    {
        $id = 123;
        $transport = $this->createTransportMock(
            'GetOrderInfo',
            [
                'OrderID' => $id,
            ],
            [
                'ID' => $id,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame($id, $connector->getOrderInfo($id)->getId());
    }

    /**
     * @test
     */
    public function testGetOrderStatus(): void
    {
        $id = 321;
        $status = 'new';
        $transport = $this->createTransportMock(
            'GetOrderStatus',
            [
                'OrderID' => $id,
            ],
            [
                'Status' => $status,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame($status, $connector->getOrderStatus($id)->getStatus());
    }

    /**
     * @test
     */
    public function testDeleteOrder(): void
    {
        $id = 321;
        $transport = $this->createTransportMock(
            'DeleteOrder',
            [
                'OrderID' => $id,
            ]
        );

        $connector = new Connector($transport);
        $connector->deleteOrder($id);
    }

    /**
     * @test
     */
    public function testGetReviewsList(): void
    {
        $cities = [333, 444];
        $shops = [555, 666];
        $rating = 3;
        $report = false;
        $query = ReviewsListQuery::new()
            ->setCitiesIDs($cities)
            ->setShopsIDs($shops)
            ->setRating($rating)
            ->setReport($report);
        $id = 123;
        $id1 = 321;
        $transport = $this->createTransportMock(
            'GetReviewsList',
            [
                'CitiesIDs' => $cities,
                'ShopsIDs' => $shops,
                'Rating' => $rating,
                'Report' => $report,
            ],
            [
                [
                    'ID' => $id,
                ],
                [
                    'ID' => $id1,
                ],
            ]
        );

        $connector = new Connector($transport);
        $reviews = $connector->getReviewsList($query);

        $this->assertCount(2, $reviews);
        $this->assertSame($id, $reviews[0]->getId());
        $this->assertSame($id1, $reviews[1]->getId());
    }

    /**
     * @test
     */
    public function testGetClientReviewsList(): void
    {
        $clientId = 123;
        $query = ClientReviewsListQuery::new()->setClientId($clientId);
        $id = 123;
        $id1 = 321;
        $transport = $this->createTransportMock(
            'GetClientReviewsList',
            [
                'ClientID' => $clientId,
            ],
            [
                [
                    'ID' => $id,
                ],
                [
                    'ID' => $id1,
                ],
            ]
        );

        $connector = new Connector($transport);
        $reviews = $connector->getClientReviewsList($query);

        $this->assertCount(2, $reviews);
        $this->assertSame($id, $reviews[0]->getId());
        $this->assertSame($id1, $reviews[1]->getId());
    }

    /**
     * @test
     */
    public function testGetReviewInfo(): void
    {
        $id = 123;
        $transport = $this->createTransportMock(
            'GetReviewInfo',
            [
                'ReviewID' => $id,
            ],
            [
                'ID' => $id,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame($id, $connector->getReviewInfo($id)->getId());
    }

    /**
     * @test
     */
    public function testGetReviewsQuestions(): void
    {
        $id = 123;
        $id1 = 321;
        $transport = $this->createTransportMock(
            'GetReviewsQuestions',
            [],
            [
                [
                    'ID' => $id,
                ],
                [
                    'ID' => $id1,
                ],
            ]
        );

        $connector = new Connector($transport);
        $reviews = $connector->getReviewsQuestions();

        $this->assertCount(2, $reviews);
        $this->assertSame($id, $reviews[0]->getId());
        $this->assertSame($id1, $reviews[1]->getId());
    }

    /**
     * @test
     */
    public function testAddReviewRating(): void
    {
        $orderId = 123;
        $review = 'test';
        $rating = 1;
        $photo = 'test.png';
        $query = AddReviewRatingQuery::new()
            ->setOrderId($orderId)
            ->setReview($review)
            ->setRating($rating)
            ->setPhoto($photo);
        $reviewId = 321;
        $transport = $this->createTransportMock(
            'AddReview',
            [
                'OrderID' => $orderId,
                'Review' => $review,
                'Rating' => $rating,
                'Photo' => $photo,
            ],
            [
                'ID' => $reviewId,
            ]
        );

        $connector = new Connector($transport);
        $testReviewId = $connector->addReviewRating($query);

        $this->assertSame($reviewId, $testReviewId);
    }

    /**
     * @test
     */
    public function testAddReviewQuestions(): void
    {
        $orderId = 123;
        $review = 'test';
        $questionId = 3;
        $rating = 1;
        $photo = 'test.png';
        $query = AddReviewQuestionsQuery::new()
            ->setOrderId($orderId)
            ->setReview($review)
            ->setQuestions([$questionId => $rating])
            ->setPhoto($photo);
        $reviewId = 321;
        $transport = $this->createTransportMock(
            'AddReview',
            [
                'OrderID' => $orderId,
                'Review' => $review,
                'Questions' => [
                    [
                        'ID' => $questionId,
                        'Rating' => $rating,
                    ],
                ],
                'Photo' => $photo,
            ],
            [
                'ID' => $reviewId,
            ]
        );

        $connector = new Connector($transport);
        $testReviewId = $connector->addReviewQuestions($query);

        $this->assertSame($reviewId, $testReviewId);
    }

    /**
     * @test
     */
    public function testGetStreetAutocompleteStatus(): void
    {
        $transport = $this->createTransportMock(
            'GetStreetAutocompleteStatus',
            [],
            [
                'Status' => true,
            ]
        );

        $connector = new Connector($transport);

        $this->assertTrue($connector->getStreetAutocompleteStatus());
    }

    /**
     * @test
     */
    public function testGetOrderOnlineStatus(): void
    {
        $transport = $this->createTransportMock(
            'GetOrderOnlineStatus',
            [],
            [
                'Status' => true,
            ]
        );

        $connector = new Connector($transport);

        $this->assertTrue($connector->getOrderOnlineStatus());
    }

    /**
     * @test
     */
    public function testGetOrderOnTimeStatus(): void
    {
        $transport = $this->createTransportMock(
            'GetOrderOnTimeStatus',
            [],
            [
                'Status' => true,
            ]
        );

        $connector = new Connector($transport);

        $this->assertTrue($connector->getOrderOnTimeStatus());
    }

    /**
     * @test
     */
    public function testGetPayCardStatus(): void
    {
        $transport = $this->createTransportMock(
            'GetPayCardStatus',
            [],
            [
                'Status' => true,
            ]
        );

        $connector = new Connector($transport);

        $this->assertTrue($connector->getPayCardStatus());
    }

    /**
     * @test
     */
    public function testGetPayOnlineStatus(): void
    {
        $transport = $this->createTransportMock(
            'GetPayOnlineStatus',
            [],
            [
                'Status' => true,
            ]
        );

        $connector = new Connector($transport);

        $this->assertTrue($connector->getPayOnlineStatus());
    }

    /**
     * @test
     */
    public function testFindCities(): void
    {
        $city = 'test';
        $id = '123';
        $id1 = '321';
        $transport = $this->createTransportMock(
            'SearchCitiesInKLADR',
            [
                'City' => $city,
            ],
            [
                [
                    'id' => $id,
                ],
                [
                    'id' => $id1,
                ],
            ]
        );

        $connector = new Connector($transport);
        $cities = $connector->findCities($city);

        $this->assertCount(2, $cities);
        $this->assertSame($id, $cities[0]->getId());
        $this->assertSame($id1, $cities[1]->getId());
    }

    /**
     * @test
     */
    public function testFindStreets(): void
    {
        $cityId = 22;
        $street = 'test';
        $id = '123';
        $id1 = '321';
        $transport = $this->createTransportMock(
            'SearchStreetsInCity',
            [
                'CityID' => $cityId,
                'Street' => $street,
            ],
            [
                [
                    'id' => $id,
                ],
                [
                    'id' => $id1,
                ],
            ]
        );

        $connector = new Connector($transport);
        $streets = $connector->findStreets($cityId, $street);

        $this->assertCount(2, $streets);
        $this->assertSame($id, $streets[0]->getId());
        $this->assertSame($id1, $streets[1]->getId());
    }

    /**
     * @test
     */
    public function testFindHouses(): void
    {
        $streetId = '123';
        $number = '23';
        $id = '123';
        $id1 = '321';
        $transport = $this->createTransportMock(
            'SearchHouseOnStreet',
            [
                'StreetID' => $streetId,
                'House' => $number,
            ],
            [
                [
                    'id' => $id,
                ],
                [
                    'id' => $id1,
                ],
            ]
        );

        $connector = new Connector($transport);
        $houses = $connector->findHouses($streetId, $number);

        $this->assertCount(2, $houses);
        $this->assertSame($id, $houses[0]->getId());
        $this->assertSame($id1, $houses[1]->getId());
    }

    /**
     * @test
     */
    public function testGetClientLastAddr(): void
    {
        $phone = '79999999999';
        $address = 'test';
        $transport = $this->createTransportMock(
            'GetClientLastAddr',
            [
                'Phone' => $phone,
            ],
            [
                'Address' => $address,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame($address, $connector->getClientLastAddr($phone));
    }

    /**
     * @test
     */
    public function testGetClientAddrs(): void
    {
        $orderId = 12;
        $phone = '79999999999';
        $query = ClientAddrsQuery::new()->setOrderId($orderId)->setPhone($phone);
        $city = 'test';
        $transport = $this->createTransportMock(
            'GetClientAddrs',
            [
                'OrderID' => $orderId,
                'Phone' => $phone,
            ],
            [
                'City' => $city,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame($city, $connector->getClientAddrs($query)->getCity());
    }

    /**
     * @test
     */
    public function testGetBaseShop(): void
    {
        $cityId = 12;
        $address = 'test';
        $query = BaseShopQuery::new()->setCityId($cityId)->setAddress($address);
        $shopId = 123;
        $transport = $this->createTransportMock(
            'GetBaseShop',
            [
                'CityID' => $cityId,
                'Address' => $address,
            ],
            [
                'ShopID' => $shopId,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame($shopId, $connector->getBaseShop($query));
    }

    /**
     * @test
     */
    public function testGetBaseShop70Exception(): void
    {
        $cityId = 12;
        $address = 'test';
        $query = BaseShopQuery::new()->setCityId($cityId)->setAddress($address);
        $transport = $this->createTransportMock(
            'GetBaseShop',
            [
                'CityID' => $cityId,
                'Address' => $address,
            ],
            new ApiException('test', 70)
        );

        $connector = new Connector($transport);

        $this->assertNull($connector->getBaseShop($query));
    }

    /**
     * @test
     */
    public function testGetBaseShopNon70Exception(): void
    {
        $cityId = 12;
        $address = 'test';
        $query = BaseShopQuery::new()->setCityId($cityId)->setAddress($address);
        $transport = $this->createTransportMock(
            'GetBaseShop',
            [
                'CityID' => $cityId,
                'Address' => $address,
            ],
            new ApiException('test', 321)
        );

        $connector = new Connector($transport);

        $this->expectException(ApiException::class);
        $connector->getBaseShop($query);
    }

    /**
     * @test
     */
    public function testCheckAddressInZones(): void
    {
        $orderId = 12;
        $address = 'test';
        $query = CheckAddressInZonesQuery::new()->setOrderId($orderId)->setAddress($address);
        $transport = $this->createTransportMock(
            'CheckAddressInZones',
            [
                'OrderID' => $orderId,
                'Address' => $address,
            ]
        );

        $connector = new Connector($transport);

        $this->assertTrue($connector->checkAddressInZones($query));
    }

    /**
     * @test
     */
    public function testCheckAddressInZonesCode70Exception(): void
    {
        $orderId = 12;
        $address = 'test';
        $query = CheckAddressInZonesQuery::new()->setOrderId($orderId)->setAddress($address);
        $transport = $this->createTransportMock(
            'CheckAddressInZones',
            [
                'OrderID' => $orderId,
                'Address' => $address,
            ],
            new ApiException('test', 70)
        );

        $connector = new Connector($transport);

        $this->assertFalse($connector->checkAddressInZones($query));
    }

    /**
     * @test
     */
    public function testCheckAddressInZonesCodeNon70Exception(): void
    {
        $orderId = 12;
        $address = 'test';
        $query = CheckAddressInZonesQuery::new()->setOrderId($orderId)->setAddress($address);
        $transport = $this->createTransportMock(
            'CheckAddressInZones',
            [
                'OrderID' => $orderId,
                'Address' => $address,
            ],
            new ApiException('test', 123)
        );

        $connector = new Connector($transport);

        $this->expectException(ApiException::class);
        $connector->checkAddressInZones($query);
    }

    /**
     * @test
     */
    public function testGetOrderFinalInfo(): void
    {
        $orderId = 12;

        $transport = $this->createTransportMock(
            'GetOrderFinalInfo',
            [
                'OrderID' => $orderId,
            ],
            [
                'ID' => $orderId,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame($orderId, $connector->getOrderFinalInfo($orderId)->getId());
    }

    /**
     * @test
     */
    public function testBonusPayOrder(): void
    {
        $orderId = 12;
        $bonus = 123;
        $query = BonusPayOrderQuery::new()->setOrderId($orderId)->setBonus($bonus);

        $transport = $this->createTransportMock(
            'BonusPayOrder',
            [
                'OrderID' => $orderId,
                'Bonus' => $bonus,
            ]
        );

        $connector = new Connector($transport);
        $connector->bonusPayOrder($query);
    }

    /**
     * @test
     */
    public function testAcceptOrder(): void
    {
        $orderId = 12;
        $pay = 'external';
        $confirm = true;
        $cashChange = 123123;
        $time = '23:32';
        $onTime = new DateTimeImmutable("2020-10-10 {$time}");
        $comment = 'test';

        $query = AcceptOrderQuery::new()
            ->setOrderId($orderId)
            ->setPay($pay)
            ->setConfirm($confirm)
            ->setCashChange($cashChange)
            ->setOnTime($onTime)
            ->setComment($comment);

        $transport = $this->createTransportMock(
            'AcceptOrder',
            [
                'OrderID' => $orderId,
                'Pay' => $pay,
                'Confirmation' => $confirm,
                'CashChange' => $cashChange,
                'OnTime' => $time,
                'Comment' => $comment,
            ]
        );

        $connector = new Connector($transport);
        $connector->acceptOrder($query);
    }

    /**
     * @test
     */
    public function testCreateOrder(): void
    {
        $cityId = 123;
        $total = 456.0;
        $source = ['key' => 'value'];

        $listItemArray = ['test' => 'test value', 'Mods' => []];
        $listItem = $this->getMockBuilder(OrderListItem::class)->getMock();
        $listItem->method('jsonSerialize')->willReturn($listItemArray);

        $listItemArray1 = ['test1' => 'test value 1', 'Mods' => ['mod' => 'mod value']];
        $listItem1 = $this->getMockBuilder(OrderListItem::class)->getMock();
        $listItem1->method('jsonSerialize')->willReturn($listItemArray1);

        $query = CreateOrderQuery::new()
            ->setCityId($cityId)
            ->setTotal($total)
            ->setSource($source)
            ->setList(
                [
                    $listItem,
                    $listItem1,
                ]
            );
        $orderId = 12;

        $transport = $this->createTransportMock(
            'CreateOrder',
            [
                'CityID' => $cityId,
                'Total' => $total,
                'Source' => json_encode($source),
                'List' => [
                    ['test' => 'test value'],
                    ['test1' => 'test value 1', 'Mods' => ['mod' => 'mod value']],
                ],
            ],
            [
                'ID' => $orderId,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame($orderId, $connector->createOrder($query));
    }

    /**
     * @test
     */
    public function testBindClient(): void
    {
        $bindInfoArray = ['key' => 'value'];
        $bindInfo = $this->getMockBuilder(BindClientIdShopId::class)->getMock();
        $bindInfo->method('jsonSerialize')->willReturn($bindInfoArray);
        $query = BindClientQuery::new()->setBindInfo($bindInfo);

        $orderId = 12;

        $transport = $this->createTransportMock(
            'BindClient',
            $bindInfoArray,
            [
                'OrderID' => $orderId,
            ]
        );

        $connector = new Connector($transport);

        $this->assertSame($orderId, $connector->bindClient($query)->getOrderId());
    }

    /**
     * Create mock for transport object with set data.
     *
     * @param string          $method
     * @param array           $params
     * @param Throwable|array $result
     *
     * @return Transport
     */
    private function createTransportMock(string $method, array $params = [], $result = []): Transport
    {
        $transport = $this->getMockBuilder(Transport::class)->disableOriginalConstructor()->getMock();

        $sendRequestMethod = $transport->expects($this->once())->method('sendRequest');
        if ($result instanceof Throwable) {
            $sendRequestMethod->willThrowException($result);
        } else {
            $sendRequestMethod->willReturnCallback(
                function (TransportRequest $request) use ($method, $params, $result): TransportResponse {
                    if ($request->getMethod() !== $method || $request->getParams() !== $params) {
                        $message = "Mock for this request isn't found.";
                        $message .= " Have {$method} - " . json_encode($params) . '.';
                        $message .= " Got {$request->getMethod()} - " . json_encode($request->getParams());
                        throw new ApiException($message);
                    }

                    $response = $this->getMockBuilder(TransportResponse::class)->disableOriginalConstructor()->getMock();
                    $response->method('getData')->willReturn($result);

                    return $response;
                }
            );
        }

        return $transport;
    }
}
