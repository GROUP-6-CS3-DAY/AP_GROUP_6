<?php

namespace App\Application\Exceptions;

class ServiceNotFoundException extends \Exception
{
    public function __construct(string $message = "Service not found", int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
