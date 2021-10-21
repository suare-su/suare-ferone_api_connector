<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use SuareSu\FeroneApiConnector\Exception\ApiException;
use SuareSu\FeroneApiConnector\Exception\TransportException;
use Throwable;

/**
 * Transport object that can create a request, send it to Ferone and parse a response.
 */
class FeroneTransport
{
    private FeroneConfig $config;

    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    public function __construct(
        FeroneConfig $config,
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->config = $config;
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * Send request to API and return parsed response.
     *
     * @param FeroneRequest $request
     *
     * @return FeroneResponse
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function sendRequest(FeroneRequest $request): FeroneResponse
    {
        $response = $this->sendRequestInternal($request);

        return $this->parseResponse($response, $request);
    }

    /**
     * Send request using underlying client.
     *
     * @param FeroneRequest $request
     *
     * @return ResponseInterface
     */
    private function sendRequestInternal(FeroneRequest $request): ResponseInterface
    {
        try {
            $payload = $this->createPayloadStream($request);
            $psrRequest = $this->requestFactory
                ->createRequest(
                    'POST',
                    $this->config->getUrl()
                )
                ->withBody($payload)
            ;
            $response = $this->client->sendRequest($psrRequest);
        } catch (Throwable $e) {
            throw TransportException::wrapException($e, $request);
        }

        return $response;
    }

    /**
     * Parse a response to an array.
     *
     * @param ResponseInterface $response
     * @param FeroneRequest     $request
     *
     * @return FeroneResponse
     */
    private function parseResponse(ResponseInterface $response, FeroneRequest $request): FeroneResponse
    {
        $stringPayload = (string) $response->getBody();

        try {
            $jsonPayload = json_decode($stringPayload, true, 512, \JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            throw TransportException::wrapException($e, $request);
        }

        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 300) {
            throw ApiException::invalidStatusCode($response->getStatusCode(), $jsonPayload);
        }

        if (!empty($jsonPayload['error'])) {
            throw ApiException::errorInResponse($jsonPayload);
        }

        return new FeroneResponse($response->getStatusCode(), $jsonPayload);
    }

    /**
     * Creates stream for request payload.
     *
     * @param FeroneRequest $request
     *
     * @return StreamInterface
     */
    private function createPayloadStream(FeroneRequest $request): StreamInterface
    {
        $payload = ['method' => $request->getMethod()];

        if (!empty($request->getParams())) {
            $payload['params'] = $request->getParams();
        }

        $payloadJson = json_encode($payload, \JSON_THROW_ON_ERROR);

        return $this->streamFactory->createStream($payloadJson);
    }
}
