<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Exceptions\AuthenticationFailedException;
use App\Exceptions\TooManyLoginAttemptsException;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Api\BaseController;


use App\Services\V1\User\UserService;


class UserController extends BaseController
{
    private $userService;


    public function __construct(
        UserService $userService
    ) {

        $this->userService = $userService;
    }

    public function userResolver()
    {
        return $this->userService->resolver();
    }

}
