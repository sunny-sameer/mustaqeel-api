<?php


namespace App\Exceptions;

use Exception;

class TooManyLoginAttemptsException extends Exception
{
    protected $message = 'Too many attempts. Please try again later.';
}
