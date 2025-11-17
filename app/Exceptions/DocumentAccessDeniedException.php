<?php

namespace App\Exceptions;

use Exception;

class DocumentAccessDeniedException extends Exception
{
    protected $message = 'Access to document denied';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if ($message === null) {
            $message = $this->message;
        }
        
        parent::__construct($message, $code, $previous);
    }
}
