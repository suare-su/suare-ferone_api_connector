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
class Transport
{
    private const ERROR_STATUSES = [
        400 => 'Bad or poorly structured request',
        401 => 'Request isn\'t authorized',
        403 => 'Method is forbidden',
        404 => 'Method isn\'t found',
        500 => 'Server side error',
        503 => 'API is unavailable',
    ];

    private TransportConfig $config;

    private ClientInterface $client;

    private RequestFactoryInterface $requestFactory;

    private StreamFactoryInterface $streamFactory;

    public function __construct(
        TransportConfig $config,
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
     * @param TransportRequest $request
     *
     * @return TransportResponse
     *
     * @throws ApiException
     * @throws TransportException
     */
    public function sendRequest(TransportRequest $request): TransportResponse
    {
        $response = $this->sendRequestInternal($request);

        return $this->parseResponse($response, $request);
    }

    /**
     * Send request using underlying client.
     *
     * @param TransportRequest $request
     *
     * @return ResponseInterface
     */
    private function sendRequestInternal(TransportRequest $request): ResponseInterface
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
            throw new TransportException($e->getMessage(), 0, $e);
        }

        return $response;
    }

    /**
     * Parse a response to an array.
     *
     * @param ResponseInterface $response
     * @param TransportRequest  $request
     *
     * @return TransportResponse
     */
    private function parseResponse(ResponseInterface $response, TransportRequest $request): TransportResponse
    {
        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 300) {
            throw new ApiException($this->createBadStatusCodeMessage($response->getStatusCode()));
        }

        try {
            $stringPayload = (string) $response->getBody();
            $jsonPayload = json_decode($stringPayload, true, 512, \JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            throw new TransportException($e->getMessage(), 0, $e);
        }

        $feroneResponse = new TransportResponse($jsonPayload);

        if ($feroneResponse->hasError()) {
            throw new ApiException($this->createApiErrorMessage($feroneResponse));
        }

        return $feroneResponse;
    }

    /**
     * Creates stream for request payload.
     *
     * @param TransportRequest $request
     *
     * @return StreamInterface
     */
    private function createPayloadStream(TransportRequest $request): StreamInterface
    {
        $payload = ['method' => $request->getMethod()];

        if (!empty($request->getParams())) {
            $payload['params'] = $request->getParams();
        }

        $payloadJson = json_encode($payload, \JSON_THROW_ON_ERROR);

        return $this->streamFactory->createStream($payloadJson);
    }

    /**
     * Create an error message when API returns a bad status code.
     *
     * @param int $statusCode
     *
     * @return string
     */
    private function createBadStatusCodeMessage(int $statusCode): string
    {
        return sprintf(
            'Bad status code in response %s (%s)',
            $statusCode,
            self::ERROR_STATUSES[$statusCode] ?? ''
        );
    }

    /**
     * Create an error message when API returns an error.
     *
     * @param TransportResponse $response
     *
     * @return string
     */
    private function createApiErrorMessage(TransportResponse $response): string
    {
        return sprintf(
            'API error #%s (%s)',
            $response->getError(),
            $response->getErrorDescription()
        );
    }
}
