<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Transport;

use InvalidArgumentException;
use SuareSu\FeroneApiConnector\Tests\BaseTestCase;
use SuareSu\FeroneApiConnector\Transport\FeroneConfig;

/**
 * @internal
 */
class FeroneConfigTest extends BaseTestCase
{
    /**
     * @test
     */
    public function testContructWrongUrlException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new FeroneConfig('test');
    }

    /**
     * @test
     */
    public function testGetUrl(): void
    {
        $url = 'http://test.ru';

        $config = new FeroneConfig($url);

        $this->assertSame($url, $config->getUrl());
    }
}
