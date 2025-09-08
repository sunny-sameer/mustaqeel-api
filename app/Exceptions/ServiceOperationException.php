<?php


namespace App\Exceptions;

use Exception;

class ServiceOperationException extends Exception
{
    protected $context;

    public function __construct(string $message = "Service operation failed", array $context = [], int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
