<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ProductNotFoundApiException extends ApiException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct("Product not found", Response::HTTP_NOT_FOUND, $previous);
    }
}
