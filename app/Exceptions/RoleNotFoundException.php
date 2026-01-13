<?php

namespace App\Exceptions;

use Exception;

class RoleNotFoundException extends Exception
{
    protected $message = 'Invalid role.';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if ($message === null) {
            $message = $this->message;
        }

        parent::__construct($message, $code, $previous);
    }
}
