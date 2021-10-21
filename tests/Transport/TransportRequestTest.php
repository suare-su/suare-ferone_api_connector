<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Transport;

use SuareSu\FeroneApiConnector\Tests\BaseTestCase;
use SuareSu\FeroneApiConnector\Transport\TransportRequest;

/**
 * @internal
 */
class TransportRequestTest extends BaseTestCase
{
    /**
     * @test
     */
    public function testGetMethod(): void
    {
        $method = 'method';
        $params = ['key' => 'value'];

        $request = new TransportRequest($method, $params);

        $this->assertSame($method, $request->getMethod());
    }

    /**
     * @test
     */
    public function testGetParams(): void
    {
        $method = 'method';
        $params = ['key' => 'value'];

        $request = new TransportRequest($method, $params);

        $this->assertSame($params, $request->getParams());
    }
}
