<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidDataApiException extends ApiException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct("Invalid data", Response::HTTP_BAD_REQUEST, $previous);
    }
}
