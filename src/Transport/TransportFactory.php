<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Psr\Log\LoggerInterface;

/**
 * Factory object that can create a transport object using different clients.
 *
 * @psalm-consistent-constructor
 */
class TransportFactory
{
    private string $url = '';

    private string $authKey = '';

    private ?LoggerInterface $logger = null;

    /**
     * Create and return new instance of fabric.
     *
     * @return TransportFactory
     */
    public static function new(): self
    {
        return new static();
    }

    /**
     * Set API url for transport object that will be created.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set authorization key for transport object that will be created.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setAuthKey(string $authKey): self
    {
        $this->authKey = $authKey;

        return $this;
    }

    /**
     * Set logger object transport object that will be created.
     *
     * @param LoggerInterface|null $logger
     *
     * @return $this
     */
    public function setLogger(?LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Create new transport based on guzzle http client.
     *
     * @param Client $client
     *
     * @return Transport
     *
     * @throws \InvalidArgumentException
     */
    public function createForGuzzleClient(Client $client): Transport
    {
        $config = new TransportConfig($this->url, $this->authKey);
        $factory = new HttpFactory();

        return new TransportGuzzle(
            $config,
            $client,
            $factory,
            $factory,
            $this->logger
        );
    }
}
