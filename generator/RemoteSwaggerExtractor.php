<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Generator;

use GuzzleHttp\Client;
use RuntimeException;

/**
 * Object that extracts entites from remote swagger json.
 */
class RemoteSwaggerExtractor
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function extractFrom(string $url): array
    {
        $swaggerArray = $this->loadSwaggerArray($url);

        $schemas = $swaggerArray['components']['schemas'] ?? null;
        if (!\is_array($schemas)) {
            throw new RuntimeException("Schemas aren't found in swagger array");
        }

        return $schemas;
    }

    private function loadSwaggerArray(string $url): array
    {
        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException("Bad status in response {$response->getStatusCode()}");
        }

        $payloadString = (string) $response->getBody();

        return json_decode($payloadString, true, 512, \JSON_THROW_ON_ERROR);
    }
}
