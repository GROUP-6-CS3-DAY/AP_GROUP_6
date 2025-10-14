<?php

namespace App\Application\Exceptions;

class ParticipantNotFoundException extends \Exception
{
    public function __construct(string $message = "Participant not found", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
