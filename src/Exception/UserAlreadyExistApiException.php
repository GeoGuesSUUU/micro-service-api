<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserAlreadyExistApiException extends ApiException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct("User already exist", Response::HTTP_CONFLICT, $previous);
    }
}
