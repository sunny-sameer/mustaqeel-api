<?php

namespace App\Services\V1\Admin;

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
        $roleData = Role::where('name',$role)->first();
        if ($roleData === null) {
            throw new RoleNotFoundException('Invalid path or route.');
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

            if(isset($this->requests->level) && $this->role !== 'applicant'){
                $response->assignLevel($this->requests->level['name'],$this->requests->level['position']);
            }

            if(isset($this->requests->identificationData) && $this->role == 'entity'){
                $metaData = UserMetaDataDTO::fromRequest($this->requests)->toArray();
                $response->metaData()->create($metaData);
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
            $response->assignRole($this->role);

            if(isset($this->requests->level) && $this->role !== 'applicant'){
                $response->assignLevel($this->requests->level['name'],$this->requests->level['position']);
            }

            if(isset($this->requests->identificationData) && $this->role == 'entity'){
                $metaData = UserMetaDataDTO::fromRequest($this->requests)->toArray();
                $response->metaData()->update($metaData);
            }

            DB::commit();

            return $this->success(
                data: ['user' => $response],
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

            if(isset($user->level) && $user->roles->pluck('name')->first() !== 'applicant'){
                $user->level()->delete();
            }

            if(isset($user->metaData) && $user->roles->pluck('name')->first() == 'entity'){
                $user->metaData()->delete();
            }

            if($user->roles->pluck('name')->first() == 'applicant'){
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
}
