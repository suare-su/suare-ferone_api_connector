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
    public function testGetTokenExpiryException(): void
    {
        $transport = $this->createTransportMock(
            'GetTokenExpiry',
            [],
            ['ExpiresOn' => 'test']
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
