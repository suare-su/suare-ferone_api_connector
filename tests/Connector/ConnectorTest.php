<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Connector;

use DateTimeImmutable;
use SuareSu\FeroneApiConnector\Connector\Connector;
use SuareSu\FeroneApiConnector\Exception\ApiException;
use SuareSu\FeroneApiConnector\Query\ClientBonusQuery;
use SuareSu\FeroneApiConnector\Query\ClientListQuery;
use SuareSu\FeroneApiConnector\Query\ClientOrdersListQuery;
use SuareSu\FeroneApiConnector\Query\ClientReviewsListQuery;
use SuareSu\FeroneApiConnector\Query\MenuQuery;
use SuareSu\FeroneApiConnector\Query\OrdersListQuery;
use SuareSu\FeroneApiConnector\Query\ReviewsListQuery;
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
        $transport = $this->createTransportMock(
            'SendSMS',
            [
                'Phone' => $phoneNumber,
                'Message' => $message,
            ]
        );

        $connector = new Connector($transport);

        $connector->sendSMS($phoneNumber, $message);
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
    public function testGetClientInfo(): void
    {
        $id = 123;
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

        $this->assertSame($id, $connector->getClientInfo($id)->getId());
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
        $connector->updateClientInfo($clientId, $name, $birth);
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
        $menuItems = $connector->getOrdersList($query);

        $this->assertCount(2, $menuItems);
        $this->assertSame($id, $menuItems[0]->getId());
        $this->assertSame($id1, $menuItems[1]->getId());
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
        $menuItems = $connector->getClientOrdersList($query);

        $this->assertCount(2, $menuItems);
        $this->assertSame($id, $menuItems[0]->getId());
        $this->assertSame($id1, $menuItems[1]->getId());
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
        $menuItems = $connector->getReviewsList($query);

        $this->assertCount(2, $menuItems);
        $this->assertSame($id, $menuItems[0]->getId());
        $this->assertSame($id1, $menuItems[1]->getId());
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
        $menuItems = $connector->getClientReviewsList($query);

        $this->assertCount(2, $menuItems);
        $this->assertSame($id, $menuItems[0]->getId());
        $this->assertSame($id1, $menuItems[1]->getId());
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
        $menuItems = $connector->getReviewsQuestions();

        $this->assertCount(2, $menuItems);
        $this->assertSame($id, $menuItems[0]->getId());
        $this->assertSame($id1, $menuItems[1]->getId());
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
                        throw new ApiException("Mock for this request isn't found");
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
