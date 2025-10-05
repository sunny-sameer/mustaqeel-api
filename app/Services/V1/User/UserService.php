<?php

namespace App\Services\V1\User;


use App\Models\User;


use App\DTOs\Api\V1\AddressDTO;
use App\DTOs\Api\V1\CommunicationDTO;
use App\DTOs\Api\V1\PassportDTO;
use App\DTOs\Api\V1\ProfileDTO;
use App\DTOs\Api\V1\QatarInfoDTO;


use App\Exceptions\BadRequestException;
use App\Exceptions\UserNotFoundException;
use Illuminate\Validation\ValidationException;


use App\Services\V1\BaseService;


use App\Repositories\V1\Users\UsersInterface;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


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

    public function userProfileCreateOrUpdate($requests)
    {
        DB::beginTransaction();

        try {
            $profileData = ProfileDTO::fromRequest($requests)->toArray();
            $passportData = PassportDTO::fromRequest($requests)->toArray();
            $commsData = CommunicationDTO::fromRequest($requests)->toArray();
            $addressData = AddressDTO::fromRequest($requests)->toArray();
            $qatarInfoData = QatarInfoDTO::fromRequest($requests)->toArray();


            $profile = $this->userInterface->createUpdateProfile($profileData,auth()->id());
            $passport = $this->userInterface->createUpdatePassport($passportData,auth()->id());
            $comms = $this->userInterface->createUpdateComms($commsData,auth()->id());
            $address = $this->userInterface->createUpdateAddress($addressData,auth()->id());
            $qatarInfo = $this->userInterface->createUpdateQatarInfo($qatarInfoData,auth()->id());

            DB::commit();

            return $this->success(
                data: ['profile'=>$profile,'passport'=>$passport,'comms'=>$comms,'address'=>$address,'qatarInfo'=>$qatarInfo],
                message: 'Profile created or updated successfully'
            );
        } catch (BadRequestException $e) {
            DB::rollBack();

            return $this->error(
                message: 'Profile creation or updation failed',
                errors: $e->getMessage(),
                statusCode: 500
            );
        }
    }
}
