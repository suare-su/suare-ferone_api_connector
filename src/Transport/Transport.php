<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Transport;

use SuareSu\FeroneApiConnector\Exception\ApiException;
use SuareSu\FeroneApiConnector\Exception\TransportException;

/**
 * Interface for trabsport object.
 */
interface Transport
{
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
    public function sendRequest(TransportRequest $request): TransportResponse;
}
