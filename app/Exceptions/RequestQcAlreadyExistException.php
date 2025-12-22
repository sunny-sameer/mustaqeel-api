<?php

namespace App\Exceptions;

use Exception;

class RequestQcAlreadyExistException extends Exception
{
    protected $message = 'This QC request has already been submitted.';
}
