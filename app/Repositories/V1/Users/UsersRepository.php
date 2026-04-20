<?php

namespace App\Repositories\V1\Users;


use App\Models\User;
use App\Models\Addresses;
use App\Models\Communications;
use App\Models\PassportDetails;
use App\Models\Profiles;
use App\Models\QatarInfo;
use App\Models\RoleLevel;
use Spatie\Permission\Models\Role;


use App\Repositories\V1\Core\CoreRepository;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UsersRepository extends CoreRepository implements UsersInterface
{
    private $profiles;
    private $passportDetails;
    private $communications;
    private $addresses;
    private $qatarInfo;
    private $role;
    private $userStatus = ['active', 'inactive', 'disable'];
    public function __construct(User $model, Profiles $profiles, PassportDetails $passportDetails, Communications $communications, Addresses $addresses, QatarInfo $qatarInfo, Role $role)
    {
        parent::__construct($model);

        $this->profiles = $profiles;
        $this->passportDetails = $passportDetails;
        $this->communications = $communications;
        $this->addresses = $addresses;
        $this->qatarInfo = $qatarInfo;
        $this->role = $role;
    }

    public function getUsers(): string
    {
        return response()->json(['users' => 'From Repo']);
    }

    public function getUserByEmail($email, $status = 0)
    {

        $userStatus = $this->userStatus[$status];

        return $this->model
            ->where('status', $userStatus)
            ->where('email', $email)
            ->get();
    }

    public function getUserById($id)
    {
        return $this->model->with('roles', 'levels')->where('id', $id)->first();
    }

    // public function getUserByEmailForAuth($email, $active)
    // {
    //     return $this->model->where('email', $email)->get();
    // }

    public function createUser($signUpData): User
    {
        return User::create([
            'name' => $signUpData['name'],
            'nameArabic' => $signUpData['nameArabic'],
            'email' => $signUpData['email'],
            'password' => Hash::make($signUpData['password']),
            'termsAccepted' => 1,
        ]);
    }

    public function updateUser($requestData, $id)
    {
        $user = $this->getUserById($id);
        $user->update($requestData);
        return $user;
    }

    public function assignRole(User $user, $role): void
    {
        $user->assignRole($role);
    }

    public function activateUser(User $user): void
    {
        $user->status = 'active';
        $user->save();
    }

    public function createUpdateProfile($request, $id)
    {
        return $this->profiles->updateOrCreate(['userId' => $id], $request);
    }

    public function createUpdatePassport($request, $id)
    {
        return $this->passportDetails->updateOrCreate(['userId' => $id], $request);
    }

    public function createUpdateComms($request, $id)
    {
        return $this->communications->updateOrCreate(['userId' => $id], $request);
    }

    public function createUpdateAddress($request, $id)
    {
        return $this->addresses->updateOrCreate(['userId' => $id], $request);
    }

    public function createUpdateQatarInfo($request, $id)
    {
        return $this->qatarInfo->updateOrCreate(['userId' => $id], $request);
    }

    public function getUsersByRoleAndLevel($role, $levelColumn, $levelOperator, $levelValue)
    {
        return $this->model->with(['levels'=>function ($query) use ($levelColumn){
            $query->orderBy($levelColumn,'ASC');
        }])
        ->whereHas('roles', function ($query) use ($role) {
            $query->where('type', $role);
        })->whereHas('levels', function ($query) use ($levelColumn, $levelOperator, $levelValue) {
            $query->where($levelColumn, $levelOperator, (int) $levelValue);
        })->get();
    }

    // start CRUD operation for admin portal

    public function getUsersByRole($request, $role)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;
        $model = $this->model->query();
        if ($role == 'entity') {
            $model = $model->with('levels.role', 'metaData');
        } else if ($role == 'jusour') {
            $model = $model->with('levels.role');
        }
        $model = $model->whereHas('roles', function ($query) use ($role) {
            $query->where('type', $role);
        })
            ->paginate($paginate);

        $model->map(function ($query) {
            if (isset($query->metaData)) {
                $query->metaData->meta = json_decode($query->metaData->meta, true);
            }
            return $query;
        });

        return $model;
    }

    public function showUserByRole($role, $id)
    {
        $model = $this->model->query();
        if ($role == 'jusour') {
            $model = $model->with('levels.role');
        } else if ($role == 'entity') {
            $model = $model->with('levels.role', 'metaData');
        } else if ($role == 'applicant') {
            $model = $model->with('profile', 'communication', 'passport', 'address', 'qatarInfo');
        }
        $model = $model->whereHas('roles', function ($query) use ($role) {
            $query->where('type', $role);
        })
            ->where('id', $id)->first();

        if ($role == 'jusour') {
            $model->permissions = $model->permissions() ? $model->permissions()->pluck('name') : [];
        } else if ($role == 'entity') {
            if (isset($model->metaData->meta)) {
                $model->metaData->meta = json_decode($model->metaData->meta, true);
            }
        } else if ($role == 'applicant') {
            if (isset($model->qatarInfo->value)) {
                $model->qatarInfo->value = json_decode($model->qatarInfo->value, true);
            }

            if (isset($model->communication->value)) {
                $model->communication->value = json_decode($model->communication->value, true);
            }
        }

        return $model;
    }

    // end CRUD operation for admin portal

    // start role CRUD operation for admin portal

    public function getAllRoles($request)
    {
        $paginate = isset($request['perPage']) ? $request['perPage'] : 10;
        return $this->role->with('permissions')->paginate($paginate);
    }

    public function getRole($id)
    {
        $role = $this->role->find($id);
        $role->permissions = $role->permissions() ? $role->permissions()->pluck('name') : [];
        return $role;
    }

    public function createRole($request, $permissions)
    {
        $role = $this->role->create($request);
        $role->givePermissionTo($permissions);

        return $this->getRole($role->id);
    }

    public function updateRole($request, $permissions, $id)
    {
        $role = $this->role->find($id);
        $role->update($request);
        $role->syncPermissions($permissions);

        return $this->getRole($role->id);
    }

    public function deleteRole($id)
    {
        $role = $this->role->find($id);
        $role->syncPermissions([]);
        $role->delete();

        return $role;
    }

    public function getRolesByType($type)
    {
        $roles = $this->role
            ->where('type', $type)
            ->get();

        $roles->map(function ($query) {
            $query->levels = RoleLevel::where('role_id', $query->id)->get();
            return $query;
        });

        return $roles;
    }

    // end role CRUD operation for admin portal

    // start permissions operation for admin portal

    public function getAllPermissions()
    {
        $data = [];
        $permissions = Permission::all();
        foreach ($permissions as $key => $value) {
            $data[$value->type][] = $value->name;
        }
        return $data;
    }

    public function getAllRolePermissions($roleId)
    {
        $role = $this->role->find($roleId);
        $data = [];
        foreach ($role->permissions as $key => $value) {
            $data[$value->type][] = $value->name;
        }
        return $data;
    }

    public function getAllUserPermissions($userId)
    {
        $user = $this->show($userId);
        $data = [];
        foreach ($user->permissions as $key => $value) {
            $data[$value->type][] = $value->name;
        }
        return $data;
    }

    // end permissions operation for admin portal
}
