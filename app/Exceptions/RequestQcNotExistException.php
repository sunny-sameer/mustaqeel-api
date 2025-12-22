<?php

namespace App\Exceptions;

use Exception;

class RequestQcNotExistException extends Exception
{
    protected $message = 'No QC request found.';
}
