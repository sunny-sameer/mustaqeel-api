<?php

namespace App\Repositories\V1\Users;

use App\Repositories\V1\Core\CoreRepository;

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
}
