<?php

namespace App\Http\Controllers\Api\V1\User;


use App\Http\Controllers\Api\BaseController;


class UserController extends BaseController
{
    private $userService;


    public function __construct(
        AuthService $authService
    ) {

        $this->authService = $authService;
    }


}
