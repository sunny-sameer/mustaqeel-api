<?php


namespace App\Exceptions;

use Exception;

class AuthenticationFailedException extends Exception
{
    protected $message = 'Invalid credentials.';
}
