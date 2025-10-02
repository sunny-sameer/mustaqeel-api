<?php

namespace App\Repositories\V1\Users;

use App\Repositories\V1\Core\CoreInterface;

use App\Models\User;

interface UsersInterface extends CoreInterface
{
    public function getUsers();
    public function getUserByEmail($email);
    public function getUserById($id);
    public function getUserByEmailForAuth($email);
    public function createUser($signUpData);
    public function assignRole(User $user, $role);
    public function activateUser(User $user);
    public function createUpdateProfile($request, $id);
    public function createUpdatePassport($request, $id);
    public function createUpdateComms($request, $id);
    public function createUpdateAddress($request, $id);
    public function createUpdateQatarInfo($request, $id);
}
