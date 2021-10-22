<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Connector;

use SuareSu\FeroneApiConnector\Connector\Connector;
use SuareSu\FeroneApiConnector\Exception\ApiException;
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
            ['ExpiresOn' => $date]
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
            ['Changed' => $date]
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
            ['Changed' => $date]
        );

        $connector = new Connector($transport);

        $this->assertSame(
            $date,
            $connector->getShopsLastChanged()->format('Y-m-d H:i:s')
        );
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
