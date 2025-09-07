<?php


namespace App\Exceptions;

use Exception;

class UserFoundException extends Exception
{
    protected $message = 'User with this email already Exists.';
}
