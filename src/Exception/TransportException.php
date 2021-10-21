<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Exception;

use SuareSu\FeroneApiConnector\Transport\TransportRequest;
use Throwable;

/**
 * Exception from transport layer. E.g. from guzzle.
 */
class TransportException extends Exception
{
    public static function wrapException(Throwable $e, ?TransportRequest $request = null): self
    {
        $message = 'Error while http request';
        if ($request !== null) {
            $message .= ' (' . $request->getMethod() . ', ' . json_encode($request->getParams(), \JSON_UNESCAPED_UNICODE) . ')';
        }
        $message .= ': ' . $e->getMessage();

        return new self($message, 0, $e);
    }
}
