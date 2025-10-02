<?php

namespace App\Http\Controllers\Api\V1\User;

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
        try {
            return $this->userService
            ->checkUserResolver()
            ->resolver();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

}
