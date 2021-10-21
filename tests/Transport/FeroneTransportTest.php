<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Transport;

use InvalidArgumentException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use SuareSu\FeroneApiConnector\Exception\ApiException;
use SuareSu\FeroneApiConnector\Exception\TransportException;
use SuareSu\FeroneApiConnector\Tests\BaseTestCase;
use SuareSu\FeroneApiConnector\Transport\FeroneConfig;
use SuareSu\FeroneApiConnector\Transport\FeroneRequest;
use SuareSu\FeroneApiConnector\Transport\FeroneTransport;

/**
 * @internal
 */
class FeroneTransportTest extends BaseTestCase
{
    /**
     * @test
     */
    public function testSendRequest(): void
    {
        $apiUrl = 'http://test.ru';
        $config = new FeroneConfig($apiUrl);

        $method = 'api method';
        $params = ['param_key' => 'param value'];
        $feroneRequest = new FeroneRequest($method, $params);

        $encodedRequestPayload = json_encode(['method' => $method, 'params' => $params]);
        $streamRequest = $this->getMockBuilder(StreamInterface::class)->getMock();
        $streamFactory = $this->getMockBuilder(StreamFactoryInterface::class)->getMock();
        $streamFactory->expects($this->once())
            ->method('createStream')
            ->with($this->identicalTo($encodedRequestPayload))
            ->willReturn($streamRequest);

        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $request->expects($this->once())
            ->method('withBody')
            ->with($this->identicalTo($streamRequest))
            ->willReturnSelf();

        $requestFactory = $this->getMockBuilder(RequestFactoryInterface::class)->getMock();
        $requestFactory->expects($this->once())
            ->method('createRequest')
            ->with($this->identicalTo('POST'), $this->identicalTo($apiUrl))
            ->willReturn($request);

        $data = ['data_key' => 'data value'];
        $encodedData = json_encode(['error' => 0, 'data' => $data]);
        $streamResponse = $this->getMockBuilder(StreamInterface::class)->getMock();
        $streamResponse->method('__toString')->willReturn($encodedData);
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $response->method('getBody')->willReturn($streamResponse);
        $response->method('getStatusCode')->willReturn(200);

        $client = $this->getMockBuilder(ClientInterface::class)->getMock();
        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->identicalTo($request))
            ->willReturn($response);

        $transport = new FeroneTransport($config, $client, $requestFactory, $streamFactory);
        $response = $transport->sendRequest($feroneRequest);

        $this->assertSame($data, $response->getData());
    }

    /**
     * @test
     */
    public function testSendRequestTransportException(): void
    {
        $apiUrl = 'http://test.ru';
        $config = new FeroneConfig($apiUrl);

        $method = 'api method';
        $params = ['param_key' => 'param value'];
        $feroneRequest = new FeroneRequest($method, $params);

        $streamRequest = $this->getMockBuilder(StreamInterface::class)->getMock();
        $streamFactory = $this->getMockBuilder(StreamFactoryInterface::class)->getMock();
        $streamFactory->method('createStream')->willReturn($streamRequest);

        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $request->method('withBody')->willReturnSelf();

        $requestFactory = $this->getMockBuilder(RequestFactoryInterface::class)->getMock();
        $requestFactory->method('createRequest')->willReturn($request);

        $client = $this->getMockBuilder(ClientInterface::class)->getMock();
        $client->expects($this->once())
            ->method('sendRequest')
            ->with($this->identicalTo($request))
            ->willThrowException(new InvalidArgumentException('test'));

        $transport = new FeroneTransport($config, $client, $requestFactory, $streamFactory);

        $this->expectException(TransportException::class);
        $transport->sendRequest($feroneRequest);
    }

    /**
     * @test
     */
    public function testSendRequestBrokenResonseException(): void
    {
        $apiUrl = 'http://test.ru';
        $config = new FeroneConfig($apiUrl);

        $method = 'api method';
        $params = ['param_key' => 'param value'];
        $feroneRequest = new FeroneRequest($method, $params);

        $streamRequest = $this->getMockBuilder(StreamInterface::class)->getMock();
        $streamFactory = $this->getMockBuilder(StreamFactoryInterface::class)->getMock();
        $streamFactory->method('createStream')->willReturn($streamRequest);

        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $request->method('withBody')->willReturnSelf();

        $requestFactory = $this->getMockBuilder(RequestFactoryInterface::class)->getMock();
        $requestFactory->method('createRequest')->willReturn($request);

        $streamResponse = $this->getMockBuilder(StreamInterface::class)->getMock();
        $streamResponse->method('__toString')->willReturn('{"error": ');
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $response->method('getBody')->willReturn($streamResponse);
        $response->method('getStatusCode')->willReturn(200);

        $client = $this->getMockBuilder(ClientInterface::class)->getMock();
        $client->method('sendRequest')->willReturn($response);

        $transport = new FeroneTransport($config, $client, $requestFactory, $streamFactory);

        $this->expectException(TransportException::class);
        $transport->sendRequest($feroneRequest);
    }

    /**
     * @test
     */
    public function testSendRequestStatusCodeException(): void
    {
        $apiUrl = 'http://test.ru';
        $config = new FeroneConfig($apiUrl);

        $method = 'api method';
        $params = ['param_key' => 'param value'];
        $feroneRequest = new FeroneRequest($method, $params);

        $streamRequest = $this->getMockBuilder(StreamInterface::class)->getMock();
        $streamFactory = $this->getMockBuilder(StreamFactoryInterface::class)->getMock();
        $streamFactory->method('createStream')->willReturn($streamRequest);

        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $request->method('withBody')->willReturnSelf();

        $requestFactory = $this->getMockBuilder(RequestFactoryInterface::class)->getMock();
        $requestFactory->method('createRequest')->willReturn($request);

        $streamResponse = $this->getMockBuilder(StreamInterface::class)->getMock();
        $streamResponse->method('__toString')->willReturn('[]');
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $response->method('getBody')->willReturn($streamResponse);
        $response->method('getStatusCode')->willReturn(500);

        $client = $this->getMockBuilder(ClientInterface::class)->getMock();
        $client->method('sendRequest')->willReturn($response);

        $transport = new FeroneTransport($config, $client, $requestFactory, $streamFactory);

        $this->expectException(ApiException::class);
        $transport->sendRequest($feroneRequest);
    }

    /**
     * @test
     */
    public function testSendRequestApiErrorException(): void
    {
        $apiUrl = 'http://test.ru';
        $config = new FeroneConfig($apiUrl);

        $method = 'api method';
        $params = ['param_key' => 'param value'];
        $feroneRequest = new FeroneRequest($method, $params);

        $streamRequest = $this->getMockBuilder(StreamInterface::class)->getMock();
        $streamFactory = $this->getMockBuilder(StreamFactoryInterface::class)->getMock();
        $streamFactory->method('createStream')->willReturn($streamRequest);

        $request = $this->getMockBuilder(RequestInterface::class)->getMock();
        $request->method('withBody')->willReturnSelf();

        $requestFactory = $this->getMockBuilder(RequestFactoryInterface::class)->getMock();
        $requestFactory->method('createRequest')->willReturn($request);

        $streamResponse = $this->getMockBuilder(StreamInterface::class)->getMock();
        $streamResponse->method('__toString')->willReturn('{"error": 1, "errorDescription": "error"}');
        $response = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $response->method('getBody')->willReturn($streamResponse);
        $response->method('getStatusCode')->willReturn(200);

        $client = $this->getMockBuilder(ClientInterface::class)->getMock();
        $client->method('sendRequest')->willReturn($response);

        $transport = new FeroneTransport($config, $client, $requestFactory, $streamFactory);

        $this->expectException(ApiException::class);
        $transport->sendRequest($feroneRequest);
    }
}
