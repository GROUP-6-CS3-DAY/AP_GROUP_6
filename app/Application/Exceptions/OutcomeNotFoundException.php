<?php

namespace App\Application\Exceptions;

class OutcomeNotFoundException extends \Exception
{
    public function __construct(string $message = "Outcome not found", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
