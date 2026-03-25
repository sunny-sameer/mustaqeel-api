<?php

namespace App\Services\V1\Admin;

use App\DTOs\V1\User\RoleDTO;
use App\DTOs\V1\User\UserDTO;
use App\DTOs\V1\User\UserMetaDataDTO;
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
        $roleCount = Role::where('type',$role)->count();
        if ($roleCount === 0) {
            throw new RoleNotFoundException('Invalid path or route.');
        }
        $this->role = $role;
        return $this;
    }

    public function roleExistsById($roleId)
    {
        $role = $this->usersInterface->getRole($roleId);
        if ($role === null) {
            throw new RoleNotFoundException('Invalid path or route.');
        }
        return $this;
    }

    public function setInput(Request $request)
    {
        $this->requests = $request;
        return $this;
    }

    public function userExists()
    {
        $this->user = User::with('levels')->find(auth()->id());

        if (!$this->user) {
            throw new UserNotFoundException();
        }

        return $this;
    }

    public function userExistsById($userId)
    {
        $user = User::find($userId);

        if ($user === null) {
            throw new UserNotFoundException('Invalid path or route.');
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
            $roles = [];
            foreach ($this->requests->level as $key => $value) {
                array_push($roles,$value['role']);
            }

            $response->assignRole($roles);


            if(isset($this->requests->level) && !in_array('applicant',$roles)){
                $response->assignMultiLevel($this->requests->level);
            }

            if(isset($this->requests->identificationData) && $this->role == 'entity'){
                $metaData = UserMetaDataDTO::fromRequest($this->requests)->toArray();
                $response->metaData()->create($metaData);
            }

            if(isset($this->requests->permissions)){
                $response->givePermissionTo($this->requests->permissions);
            }

            DB::commit();

            $user = $this->usersInterface->showUserByRole($this->role,$response->id);
            return $this->success(
                data: ['user' => $user],
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

    public function updateUser($id)
    {
        DB::beginTransaction();

        try {
            $user = $this->usersInterface->showUserByRole($this->role, $id);
            if($user == null){
                throw new UserNotFoundException('No '.$this->role.' found.');
            }
            $userData = UserDTO::fromRequest($this->requests,$user->id)->toArray();
            $response = $this->usersInterface->update($id,$userData);

            $roles = [];
            foreach ($this->requests->level as $key => $value) {
                array_push($roles,$value['role']);
            }
            $response->assignRole([]);
            $response->assignRole($roles);

            if(isset($this->requests->level) && !in_array('applicant',$roles)){
                $response->assignMultiLevel([]);
                $response->assignMultiLevel($this->requests->level);
            }

            if(isset($this->requests->identificationData) && $this->role == 'entity'){
                $metaData = UserMetaDataDTO::fromRequest($this->requests)->toArray();
                $response->metaData()->update($metaData);
            }

            if(isset($this->requests->permissions) && $this->role == 'jusour'){
                $response->syncPermissions([]);
                $response->syncPermissions($this->requests->permissions);
            }

            DB::commit();

            $user = $this->usersInterface->showUserByRole($this->role,$response->id);
            return $this->success(
                data: ['user' => $user],
                message: ucfirst($this->role).' updated successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: ucfirst($this->role).' updation failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function deleteUser($id)
    {
        DB::beginTransaction();

        try {
            $user = $this->usersInterface->showUserByRole($this->role, $id);
            if($user == null){
                throw new UserNotFoundException('No '.$this->role.' found.');
            }

            if(isset($user->metaData) && $this->role == 'entity'){
                $user->metaData()->delete();
            }

            if($this->role == 'applicant'){
                $user->profile()->delete();
                $user->communication()->delete();
                $user->passport()->delete();
                $user->address()->delete();
                $user->qatarInfo()->delete();
            }

            $this->usersInterface->destroy($id);

            DB::commit();

            return $this->success(
                data: ['user' => $user],
                message: ucfirst($this->role).' deleted successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: ucfirst($this->role).' deletion failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function getAllRoles()
    {
        $role = $this->usersInterface->getAllRoles($this->requests);

        return $this->success(
            data: ['role' => $role],
            message: 'Roles fetched successfully'
        );
    }

    public function getRole($id)
    {
        $role = $this->usersInterface->getRole($id);

        return $this->success(
            data: ['role' => $role],
            message: 'Role fetched successfully'
        );
    }

    public function createRole()
    {
        DB::beginTransaction();

        try {
            $roleData = RoleDTO::fromRequest($this->requests)->toArray();
            $permissions = $this->requests?->permissions;
            $response = $this->usersInterface->createRole($roleData,$permissions);

            DB::commit();

            return $this->success(
                data: ['role' => $response],
                message: 'Role created successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: 'Role creation failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function updateRole($id)
    {
        DB::beginTransaction();

        try {
            $roleData = RoleDTO::fromRequest($this->requests)->toArray();
            $permissions = $this->requests?->permissions;
            $response = $this->usersInterface->updateRole($roleData,$permissions,$id);

            DB::commit();

            return $this->success(
                data: ['role' => $response],
                message: 'Role updated successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: 'Role updation failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function deleteRole($id)
    {
        DB::beginTransaction();

        try {
            $role = $this->usersInterface->getRole($id);
            $this->usersInterface->deleteRole($id);
            DB::commit();

            return $this->success(
                data: ['role' => $role],
                message: 'Role deleted successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: 'Role deletion failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function getRolesByType($type)
    {
        $role = $this->usersInterface->getRolesByType($type);

        return $this->success(
            data: ['role' => $role],
            message: 'Roles by type fetched successfully'
        );
    }

    public function getAllPermissions()
    {
        $permissions = $this->usersInterface->getAllPermissions();

        return $this->success(
            data: ['permissions' => $permissions],
            message: 'Permissions fetched successfully'
        );
    }

    public function getAllRolePermissions($roleId)
    {
        $permissions = $this->usersInterface->getAllRolePermissions($roleId);

        return $this->success(
            data: ['permissions' => $permissions],
            message: 'Role Permissions fetched successfully'
        );
    }

    public function getAllUserPermissions($userId)
    {
        $permissions = $this->usersInterface->getAllUserPermissions($userId);

        return $this->success(
            data: ['permissions' => $permissions],
            message: 'User Permissions fetched successfully'
        );
    }
}
