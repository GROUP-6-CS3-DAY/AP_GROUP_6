<?php

namespace App\Application\Exceptions;

class FacilityNotFoundException extends \Exception
{
    public function __construct(string $message = "Facility not found", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
