<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Transport;

use GuzzleHttp\Client;
use SuareSu\FeroneApiConnector\Tests\BaseTestCase;
use SuareSu\FeroneApiConnector\Transport\Transport;
use SuareSu\FeroneApiConnector\Transport\TransportFactory;

/**
 * @internal
 */
class TransportFactoryTest extends BaseTestCase
{
    /**
     * @test
     */
    public function testCreateForGuzzleClient(): void
    {
        $url = 'http://test.ru';
        $client = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();

        $factory = new TransportFactory();
        $transport = $factory->setUrl($url)->createForGuzzleClient($client);

        $this->assertInstanceOf(Transport::class, $transport);
    }
}