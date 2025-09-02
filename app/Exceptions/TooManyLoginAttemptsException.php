<?php


namespace App\Exceptions;

use Exception;

class TooManyLoginAttemptsException extends Exception
{
    protected $message = 'Too many login attempts. Please try again later.';
}
