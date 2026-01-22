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
    public function getUsersByRoleAndLevel($role,$levelColumn,$levelOperator,$levelValue);

    // start user CRUD operation for admin portal

    public function getUsersByRole($request,$role);
    public function showUserByRole($role,$id);

    // end user CRUD operation for admin portal

    // start role CRUD operation for admin portal

    public function getAllRoles($request);
    public function getRole($id);
    public function createRole($request,$permissions);
    public function updateRole($request,$permissions,$id);
    public function deleteRole($id);
    public function getRolesByType($type);

    // end role CRUD operation for admin portal

    // start permissions operation for admin portal

    public function getAllPermissions();
    public function getAllRolePermissions($roleId);
    public function getAllUserPermissions($userId);

    // end permissions operation for admin portal
}
