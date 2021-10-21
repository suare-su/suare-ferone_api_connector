<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Transport;

use InvalidArgumentException;
use SuareSu\FeroneApiConnector\Tests\BaseTestCase;
use SuareSu\FeroneApiConnector\Transport\TransportConfig;

/**
 * @internal
 */
class TransportConfigTest extends BaseTestCase
{
    /**
     * @test
     */
    public function testContructWrongUrlException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new TransportConfig('test');
    }

    /**
     * @test
     */
    public function testGetUrl(): void
    {
        $url = 'http://test.ru    ';

        $config = new TransportConfig($url);

        $this->assertSame('http://test.ru/', $config->getUrl());
    }
}
