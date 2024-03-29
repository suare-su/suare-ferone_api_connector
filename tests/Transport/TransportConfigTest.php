<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Transport;

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
        $this->expectException(\InvalidArgumentException::class);
        new TransportConfig('test', 'test', 10, 10);
    }

    /**
     * @test
     */
    public function testContructWrongTimeoutException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new TransportConfig('http://test.ru', 'test', 0, 10);
    }

    /**
     * @test
     */
    public function testContructWrongRetriesException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new TransportConfig('http://test.ru', 'test', 10, 0);
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

    /**
     * @test
     */
    public function testGetDebug(): void
    {
        $url = 'http://test.ru';
        $authKey = 'test';
        $timeout = 123;
        $retries = 2;
        $debug = true;

        $config = new TransportConfig($url, $authKey, $timeout, $retries, $debug);

        $this->assertTrue($config->getDebug());
    }

    /**
     * @test
     */
    public function testGetDebugDefault(): void
    {
        $url = 'http://test.ru';
        $authKey = 'test';

        $config = new TransportConfig($url, $authKey);

        $this->assertFalse($config->getDebug());
    }
}
