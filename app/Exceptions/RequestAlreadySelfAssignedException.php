<?php

namespace App\Exceptions;

use Exception;

class RequestAlreadySelfAssignedException extends Exception
{
    protected $message = 'This request has already been self assigned.';
}
