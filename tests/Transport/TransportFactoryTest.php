<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Tests\Transport;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
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
        $authKey = 'test';
        $client = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
        $logger = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $transport = TransportFactory::new()
            ->setUrl($url)
            ->setAuthKey($authKey)
            ->setLogger($logger)
            ->createForGuzzleClient($client);

        $this->assertInstanceOf(Transport::class, $transport);
    }
}
