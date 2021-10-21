<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Transport;

use SuareSu\FeroneApiConnector\Tests\BaseTestCase;
use SuareSu\FeroneApiConnector\Transport\FeroneResponse;

/**
 * @internal
 */
class FeroneResponseTest extends BaseTestCase
{
    /**
     * @test
     */
    public function testGetPayload(): void
    {
        $payload = ['key' => 'value'];

        $response = new FeroneResponse($payload);

        $this->assertSame($payload, $response->getPayload());
    }

    /**
     * @test
     */
    public function testGetData(): void
    {
        $data = ['data_key' => 'data value'];
        $payload = ['key' => 'value', 'data' => $data];

        $response = new FeroneResponse($payload);

        $this->assertSame($data, $response->getData());
    }

    /**
     * @test
     */
    public function testGetEmptyData(): void
    {
        $payload = ['key' => 'value'];

        $response = new FeroneResponse($payload);

        $this->assertSame([], $response->getData());
    }
}
