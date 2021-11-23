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
        new TransportConfig('test', 'test');
    }

    /**
     * @test
     */
    public function testGetUrl(): void
    {
        $url = 'http://test.ru    ';
        $authKey = 'test';

        $config = new TransportConfig($url, $authKey);

        $this->assertSame('http://test.ru/', $config->getUrl());
    }

    /**
     * @test
     */
    public function testGetAuthKey(): void
    {
        $url = 'http://test.ru';
        $authKey = 'test';

        $config = new TransportConfig($url, $authKey);

        $this->assertSame($authKey, $config->getAuthKey());
    }

    /**
     * @test
     */
    public function testGetTimeout(): void
    {
        $url = 'http://test.ru';
        $authKey = 'test';
        $timeout = 123;
        $retries = 2;

        $config = new TransportConfig($url, $authKey, $timeout, $retries);

        $this->assertSame($timeout, $config->getTimeout());
    }

    /**
     * @test
     */
    public function testGetRetries(): void
    {
        $url = 'http://test.ru';
        $authKey = 'test';
        $timeout = 123;
        $retries = 2;

        $config = new TransportConfig($url, $authKey, $timeout, $retries);

        $this->assertSame($retries, $config->getRetries());
    }
}
