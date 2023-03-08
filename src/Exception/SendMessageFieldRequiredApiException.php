<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class SendMessageFieldRequiredApiException extends ApiException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct("The \"author\" field is required", Response::HTTP_BAD_REQUEST, $previous);
    }
}
