<?php

namespace App\Services\V1\Admin;

use App\DTOs\V1\User\UserDTO;
use App\Exceptions\BadRequestException;
use App\Exceptions\RoleNotFoundException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;


use App\Repositories\V1\Users\UsersInterface;


use App\Services\V1\BaseService;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserService extends BaseService
{
    protected $usersInterface;
    private ?object $user = null;
    private ?object $requests = null;
    private ?string $role = null;

    public function __construct(
        UsersInterface $usersInterface,
    ) {
        $this->usersInterface = $usersInterface;
    }

    public function roleExists($role)
    {
        $roleData = Role::where('name',$role)->first();
        if(empty($roleData)){
            throw new RoleNotFoundException();
        }
        $this->role = $role;
        return $this;
    }

    public function setInput(Request $request)
    {
        $this->requests = $request;
        return $this;
    }

    public function userExists()
    {
        $this->user = User::with('level')->find(auth()->id());

        if (!$this->user) {
            throw new UserNotFoundException();
        }

        return $this;
    }

    public function getAllUsers()
    {
        $user = $this->usersInterface->getUsersByRole($this->requests,$this->role);

        return $this->success(
            data: ['user' => $user],
            message: 'Users fetched successfully'
        );
    }

    public function showUser($id)
    {
        $user = $this->usersInterface->showUserByRole($this->role,$id);

        return $this->success(
            data: ['user' => $user],
            message: 'User fetched successfully'
        );
    }

    public function createUser()
    {
        DB::beginTransaction();

        try {
            $userData = UserDTO::fromRequest($this->requests)->toArray();
            $response = $this->usersInterface->store($userData);
            $response->assignRole($this->role);

            if(isset($this->requests->level)){
                $response->assignLevel($this->requests->level->name,$this->requests->level->position);
            }

            if(isset($this->requests->identificationData)){
                $response->assignLevel($this->requests->level->name,$this->requests->level->position);
            }

            DB::commit();

            return $this->success(
                data: ['user' => $response],
                message: ucfirst($this->role).' created successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: ucfirst($this->role).' creation failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }
}
