<?php

namespace App\Core\DDD\Application\Exceptions;

use Illuminate\Http\Response;
use Throwable;

class ApplicationException extends \Exception
{
    private int $httpStatusCode;

    public function __construct($message = "", $code = 0, Throwable $previous = null, int $httpStatusCode = null)
    {
        parent::__construct($message, $code, $previous);
        $this->httpStatusCode = $httpStatusCode ? $httpStatusCode : Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
