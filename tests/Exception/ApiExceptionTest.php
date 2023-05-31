<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Exception;

use SuareSu\FeroneApiConnector\Exception\ApiException;
use SuareSu\FeroneApiConnector\Tests\BaseTestCase;
use SuareSu\FeroneApiConnector\Transport\TransportResponse;

/**
 * @internal
 */
class ApiExceptionTest extends BaseTestCase
{
    /**
     * @test
     */
    public function testCreate(): void
    {
        $expectedMessage = 'test message';
        $expectedResponse = new TransportResponse([]);

        $exception = ApiException::create($expectedMessage, $expectedResponse);

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame($expectedResponse, $exception->getResponse());
    }

    /**
     * @test
     */
    public function testResponse(): void
    {
        $expectedResponse = new TransportResponse([]);

        $exception = new ApiException();
        $testResponse = $exception->setResponse($expectedResponse)->getResponse();

        $this->assertSame($expectedResponse, $testResponse);
    }
}
