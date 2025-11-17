<?php

namespace App\Exceptions;

use Exception;

class DocumentNotFoundException extends Exception
{
    protected $message = 'Document not found';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if ($message === null) {
            $message = $this->message;
        }
        
        parent::__construct($message, $code, $previous);
    }
}
