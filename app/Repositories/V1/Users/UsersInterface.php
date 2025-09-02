<?php

namespace App\Repositories\V1\Users;

use App\Repositories\V1\Core\CoreInterface;

interface UsersInterface extends CoreInterface
{
    public function getUsers();
    public function getUserByEmail($email);
    public function getUserById($id);
    public function getUserByEmailForAuth($email);
}
