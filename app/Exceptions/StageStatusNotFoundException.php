<?php


namespace App\Exceptions;

use Exception;

class StageStatusNotFoundException extends Exception
{
    protected $message = 'Invalid stage or status.';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        if ($message === null) {
            $message = $this->message;
        }

        parent::__construct($message, $code, $previous);
    }
}
