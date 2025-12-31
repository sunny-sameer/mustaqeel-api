<?php

namespace App\Exceptions;

use Exception;

class RequestInvalidException extends Exception
{
    protected $message = 'Request status has already been updated.';
}
