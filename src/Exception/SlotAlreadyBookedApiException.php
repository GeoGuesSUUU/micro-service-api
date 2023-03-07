<?php

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class SlotAlreadyBookedApiException extends ApiException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct("Slot already booked", Response::HTTP_CONFLICT, $previous);
    }
}
