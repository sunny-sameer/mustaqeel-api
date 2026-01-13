<?php

namespace App\Exceptions;

use Exception;

class RoleNotFoundException extends Exception
{
    protected $message = 'Invalid role.';
}
