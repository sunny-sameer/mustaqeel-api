<?php

namespace App\Repositories\V1\Users;

use App\Repositories\V1\Core\CoreRepository;


use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UsersRepository extends CoreRepository implements UsersInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
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
}
