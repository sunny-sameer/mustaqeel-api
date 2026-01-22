<?php

namespace App\Exceptions;

use Exception;

class RequestInvalidException extends Exception
{
    protected $message = 'Request status has already been updated.';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if ($message === null) {
            $message = $this->message;
        }

        parent::__construct($message, $code, $previous);
    }
}
