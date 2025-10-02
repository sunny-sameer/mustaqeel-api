<?php

namespace App\Repositories\V1\Users;


use App\Models\User;
use App\Models\Addresses;
use App\Models\Communications;
use App\Models\PassportDetails;
use App\Models\Profiles;
use App\Models\QatarInfo;


use App\Repositories\V1\Core\CoreRepository;


use Illuminate\Support\Facades\Hash;

class UsersRepository extends CoreRepository implements UsersInterface
{
    private $profiles;
    private $passportDetails;
    private $communications;
    private $addresses;
    private $qatarInfo;

    public function __construct(User $model, Profiles $profiles, PassportDetails $passportDetails, Communications $communications, Addresses $addresses, QatarInfo $qatarInfo)
    {
        parent::__construct($model);

        $this->profiles = $profiles;
        $this->passportDetails = $passportDetails;
        $this->communications = $communications;
        $this->addresses = $addresses;
        $this->qatarInfo = $qatarInfo;
    }

    public function getUsers(): string
    {
        return response()->json(['users' => 'From Repo']);
    }

    public function getUserByEmail($email)
    {
        return $this->model->where('email', $email)->get();
    }

    public function getUserById($id)
    {
        return $this->model->where('id', $id)->with(['business:userId,id,cnameEn,cnameAr,nameEn,nameAr'])->get();
    }

    public function getUserByEmailForAuth($email)
    {
        return $this->model->where('email', $email)->get();
    }

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
        return $this->profiles->updateOrCreate(['userId'=>$id],$request);
    }

    public function createUpdatePassport($request, $id)
    {
        return $this->passportDetails->updateOrCreate(['userId'=>$id],$request);
    }

    public function createUpdateComms($request, $id)
    {
        return $this->communications->updateOrCreate(['userId'=>$id],$request);
    }

    public function createUpdateAddress($request, $id)
    {
        return $this->addresses->updateOrCreate(['userId'=>$id],$request);
    }

    public function createUpdateQatarInfo($request, $id)
    {
        return $this->qatarInfo->updateOrCreate(['userId'=>$id],$request);
    }
}
