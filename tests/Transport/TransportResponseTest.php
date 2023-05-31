<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Transport;

use SuareSu\FeroneApiConnector\Tests\BaseTestCase;
use SuareSu\FeroneApiConnector\Transport\TransportResponse;

/**
 * @internal
 */
class TransportResponseTest extends BaseTestCase
{
    /**
     * @test
     */
    public function testGetPayload(): void
    {
        $payload = ['key' => 'value'];

        $response = new TransportResponse($payload);

        $this->assertSame($payload, $response->getPayload());
    }

    /**
     * @test
     */
    public function testGetData(): void
    {
        $data = ['data_key' => 'data value'];
        $payload = ['key' => 'value', 'data' => $data];

        $response = new TransportResponse($payload);

        $this->assertSame($data, $response->getData());
    }

    /**
     * @test
     */
    public function testGetEmptyData(): void
    {
        $payload = ['key' => 'value'];

        $response = new TransportResponse($payload);

        $this->assertSame([], $response->getData());
    }

    /**
     * @test
     */
    public function testHasError(): void
    {
        $payload = ['key' => 'value', 'error' => 2];

        $response = new TransportResponse($payload);

        $this->assertTrue($response->hasError());
    }

    /**
     * @test
     */
    public function testDoesNotHaveError(): void
    {
        $payload = ['key' => 'value', 'error' => 0];

        $response = new TransportResponse($payload);

        $this->assertFalse($response->hasError());
    }

    /**
     * @test
     */
    public function testGetError(): void
    {
        $error = 123;
        $payload = ['key' => 'value', 'error' => $error];

        $response = new TransportResponse($payload);

        $this->assertSame($error, $response->getError());
    }

    /**
     * @test
     */
    public function testGetErrorDescription(): void
    {
        $errorDescription = 'test error';
        $payload = ['key' => 'value', 'errorDescription' => $errorDescription];

        $response = new TransportResponse($payload);

        $this->assertSame($errorDescription, $response->getErrorDescription());
    }

    /**
     * @test
     */
    public function testGetRawResponse(): void
    {
        $payload = ['key' => 'value'];
        $rawResponse = '{key:"value"}';

        $response = new TransportResponse($payload, $rawResponse);

        $this->assertSame($rawResponse, $response->getRawResponse());
    }

    /**
     * @test
     */
    public function testGetRawRequest(): void
    {
        $payload = ['key' => 'value'];
        $rawResponse = '{key:"value"}';
        $rawRequest = 'GET /test';

        $response = new TransportResponse($payload, $rawResponse, $rawRequest);

        $this->assertSame($rawRequest, $response->getRawRequest());
    }
}
