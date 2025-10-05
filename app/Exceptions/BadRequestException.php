<?php

namespace App\Exceptions;

use Exception;

class BadRequestException extends Exception
{
    protected $message = 'Bad Request. Please try again later.';
}
