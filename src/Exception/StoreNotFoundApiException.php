<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class StoreNotFoundApiException extends ApiException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct("Store not found", Response::HTTP_NOT_FOUND, $previous);
    }
}
