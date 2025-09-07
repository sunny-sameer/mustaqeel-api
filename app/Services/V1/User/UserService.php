<?php

namespace App\Services\V1\User;


use App\Repositories\V1\Users\UsersInterface;



class UserService
{
    private UsersInterface $userInterface;


    public function __construct(
        UsersInterface $userInterface
    ) {
        $this->userInterface = $userInterface;
    }

    public function getUserByEmail($userEmail): object
    {
        return  $this->userInterface->getUserByEmail($userEmail);
    }


    public function createUser($userEmail): object
    {
        return  $this->userInterface->getUserByEmail($userEmail);
    }
}
