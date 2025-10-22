<?php

namespace App\Exceptions;

use Exception;

class RequestNotExistException extends Exception
{
    protected $message = 'No request found.';
}
