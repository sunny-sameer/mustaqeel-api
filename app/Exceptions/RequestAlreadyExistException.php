<?php

namespace App\Exceptions;

use Exception;

class RequestAlreadyExistException extends Exception
{
    protected $message = 'This request has already been submitted.';
}
