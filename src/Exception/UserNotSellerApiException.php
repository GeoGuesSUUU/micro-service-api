<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserNotSellerApiException extends ApiException
{
    public function __construct(string $message = "User is not a seller", Exception $previous = null)
    {
        parent::__construct($message, Response::HTTP_BAD_REQUEST, $previous);
    }
}
