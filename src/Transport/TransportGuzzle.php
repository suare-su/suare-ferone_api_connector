<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use SuareSu\FeroneApiConnector\Exception\ApiException;
use SuareSu\FeroneApiConnector\Exception\TransportException;
use Throwable;

/**
 * Transport object that can create a request, send it to Ferone and parse a response.
 */
class TransportGuzzle implements Transport
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

    private ?LoggerInterface $logger;

    public function __construct(
        TransportConfig $config,
        ClientInterface $client,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        ?LoggerInterface $logger = null
    ) {
        $this->config = $config;
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     *
     * @psalm-suppress PossiblyUndefinedVariable
     */
    public function sendRequest(TransportRequest $request): TransportResponse
    {
        $tries = $this->config->getRetries() ?: 1;

        $parsedResponse = null;
        for ($i = 1; $i <= $tries; ++$i) {
            try {
                $response = $this->sendRequestInternal($request);
                $parsedResponse = $this->parseResponse($request, $response);
                break;
            } catch (TransportException $e) {
                if ($i === $tries) {
                    throw $e;
                }
            }
        }

        if (!($parsedResponse instanceof TransportResponse)) {
            throw new TransportException("Can't create parsed response.");
        }

        return $parsedResponse;
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
        if ($this->logger) {
            $this->logger->debug(
                "Sending a '{$request->getMethod()}' request to Ferone",
                [
                    'url' => $this->config->getUrl(),
                    'method' => $request->getMethod(),
                    'params' => $request->getParams(),
                ]
            );
        }

        try {
            $payload = $this->createPayloadStream($request);
            $psrRequest = $this->requestFactory
                ->createRequest('POST', $this->config->getUrl())
                ->withHeader('Authorization', $this->config->getAuthKey())
                ->withBody($payload);
            $response = $this->client->sendRequest($psrRequest);
        } catch (Throwable $e) {
            throw new TransportException($e->getMessage(), 0, $e);
        }

        return $response;
    }

    /**
     * Parse a response to an array.
     *
     * @param TransportRequest  $request
     * @param ResponseInterface $response
     *
     * @return TransportResponse
     */
    private function parseResponse(TransportRequest $request, ResponseInterface $response): TransportResponse
    {
        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();

        if ($this->logger) {
            $this->logger->debug(
                "The '{$request->getMethod()}' request to Ferone is completed",
                [
                    'url' => $this->config->getUrl(),
                    'method' => $request->getMethod(),
                    'params' => $request->getParams(),
                    'status' => $statusCode,
                    'body' => mb_strlen($body) <= 2000 ? $body : mb_substr($body, 0, 2000) . '...',
                ]
            );
        }

        if ($statusCode < 200 || $statusCode > 300) {
            throw new ApiException(
                $this->createBadStatusCodeMessage($statusCode)
            );
        }

        try {
            /** @var mixed[] */
            $jsonPayload = json_decode($body, true, 512, \JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            throw new TransportException($e->getMessage(), 0, $e);
        }

        $feroneResponse = new TransportResponse($jsonPayload);

        if ($feroneResponse->hasError()) {
            throw new ApiException(
                $this->createApiErrorMessage($feroneResponse),
                $feroneResponse->getError()
            );
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
        $payload = [
            'method' => $request->getMethod(),
            'params' => $request->getParams(),
        ];

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
