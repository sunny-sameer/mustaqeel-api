<?php

namespace App\Services\V1\User;

use App\Exceptions\UserNotFoundException;
use App\Services\V1\BaseService;
use App\Repositories\V1\Users\UsersInterface;

// use App\Http\Requests\API\V1\PostSignupRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Exceptions\ServiceOperationException;
use Illuminate\Validation\ValidationException;


use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService extends BaseService
{
    private UsersInterface $userInterface;
    private ?object $user = null;
    private ?string $token = null;


    public function __construct(
        UsersInterface $userInterface
    ) {
        $this->userInterface = $userInterface;
    }

    public function getUserByEmail($userEmail): object
    {
        return  $this->userInterface->getUserByEmail($userEmail);
    }

    private function validateUserData(array $userData): void
    {
        $rules = [
            'name'          => 'required|string|max:255',
            'nameArabic'    => ['nullable', 'regex:/^[\p{Arabic}.,، ]+$/u'],
            'email'         => 'required|email|unique:users,email',
            'password'      => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*\-]).{8,64}$/'
            ],
            'termsAccepted' => 'required|accepted',
        ];

        $validator = Validator::make($userData, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }



    public function createUser($userData, $role): array
    {
        $this->validateUserData($userData);


        DB::beginTransaction();

        try {

            $user = $this->userInterface->createUser($userData);

            $this->userInterface->assignRole($user, $role);

            $this->userInterface->activateUser($user);

            $this->createToken($user);

            DB::commit();

            return $this->success(
                data: ['user' => $user, 'token' => $this->token, 'role' => $user->roles->pluck('name')->first()],
                message: 'User created and activated successfully'
            );
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->error(
                message: 'User registration failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    public function createToken(User $user): self
    {
        $this->user = $user;
        $this->token =  $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return $this;
    }

    public function loginResponse(): array
    {
        return $this->success(
            data: ['user' => $this->user, 'token' => $this->token, 'role' => $this->user->roles->pluck('name')->first()],
            message: 'Login Successful'
        );
    }

    public function checkUserResolver()
    {
        $this->user = User::with('profile')->find(auth()->id());

        if (!$this->user) {
            throw new UserNotFoundException();
        }

        return $this;

    }

    public function resolver()
    {
        return $this->success(
            data: ['user' => $this->user, 'role' => $this->user->roles->pluck('name')->first()],
            message: 'User resolver triggered successfully'
        );
    }
}
