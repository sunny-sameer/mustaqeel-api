<?php

use App\Exceptions\UserNotFoundException;


use App\Models\User;


use App\Repositories\V1\Users\UsersInterface;


use App\Services\V1\BaseService;


class UserService extends BaseService
{
    private UsersInterface $userInterface;
    private ?object $user = null;

    public function userExists()
    {
        $this->user = User::with('profile','level')->find(auth()->id());

        if (!$this->user) {
            throw new UserNotFoundException();
        }

        return $this;
    }
}
