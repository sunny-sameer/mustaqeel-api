<?php

namespace App\Http\Controllers\Api\V1\Admin;


use Illuminate\Http\Request;


use App\Http\Controllers\Api\BaseController;


use App\Services\V1\Admin\UserService;


use App\Exceptions\BadRequestException;
use App\Exceptions\RoleNotFoundException;
use App\Exceptions\UserNotFoundException;


use App\Http\Requests\API\V1\Admin\RoleCreateRequest;
use App\Http\Requests\API\V1\Admin\RoleUpdateRequest;
use App\Http\Requests\API\V1\Admin\UserCreateRequest;
use App\Http\Requests\API\V1\Admin\UserUpdateRequest;


class UserController extends BaseController
{
    /**
     * See Swagger annotations in \App\Swaggers\V1\Admin\UserSwagger
    */


    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function users(Request $request, $role)
    {
        try {
            return $this->userService
                ->userExists()
                ->roleExists($role)
                ->setInput($request)
                ->getAllUsers();
        } catch (RoleNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function user($role, $id)
    {
        try {
            return $this->userService
                ->userExists()
                ->roleExists($role)
                ->showUser($id);
        } catch (RoleNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function createUser(UserCreateRequest $request, $role)
    {
        try {
            return $this->userService
                ->userExists()
                ->roleExists($role)
                ->setInput($request)
                ->createUser();
        } catch (RoleNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function updateUser(UserUpdateRequest $request, $role, $id)
    {
        try {
            return $this->userService
                ->userExists()
                ->roleExists($role)
                ->setInput($request)
                ->updateUser($id);
        } catch (RoleNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function deleteUser($role, $id)
    {
        try {
            return $this->userService
                ->userExists()
                ->roleExists($role)
                ->deleteUser($id);
        } catch (RoleNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function roles(Request $request)
    {
        try {
            return $this->userService
                ->setInput($request)
                ->userExists()
                ->getAllRoles();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function role($id)
    {
        try {
            return $this->userService
                ->userExists()
                ->getRole($id);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function createRole(RoleCreateRequest $request)
    {
        try {
            return $this->userService
                ->userExists()
                ->setInput($request)
                ->createRole();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function updateRole(RoleUpdateRequest $request, $id)
    {
        try {
            return $this->userService
                ->userExists()
                ->setInput($request)
                ->updateRole($id);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function deleteRole( $id)
    {
        try {
            return $this->userService
                ->userExists()
                ->deleteRole($id);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function rolesByType($type)
    {
        try {
            return $this->userService
                ->userExists()
                ->getRolesByType($type);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function permissions()
    {
        try {
            return $this->userService
                ->userExists()
                ->getAllPermissions();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function rolePermissions($roleId)
    {
        try {
            return $this->userService
                ->userExists()
                ->roleExistsById($roleId)
                ->getAllRolePermissions($roleId);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (RoleNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    public function userPermissions($userId)
    {
        try {
            return $this->userService
                ->userExists()
                ->userExistsById($userId)
                ->getAllUserPermissions($userId);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }
}
