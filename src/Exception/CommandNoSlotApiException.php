<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CommandNoSlotApiException extends ApiException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct("This command doesn't have slot", Response::HTTP_BAD_REQUEST, $previous);
    }
}
