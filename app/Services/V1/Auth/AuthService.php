<?php

namespace App\Services\V1\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


// use App\Repositories\V1\Users\UsersInterface;
// use App\Repositories\V1\Company\CompanyInterface;
// use App\Repositories\V1\Employee\EmployeeInterface;
// use App\Notifications\Core\User\UserInvitationNotification;


// use App\Hooks\User\BeforeLogin;
// use App\Hooks\User\AfterLogin;


// Exceptions
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserFoundException;
use App\Exceptions\AuthenticationFailedException;
use App\Exceptions\TooManyLoginAttemptsException;
use App\Http\Requests\API\V1\LoginRequest;
use App\Http\Requests\API\V1\SignupRequest;


use App\Services\V1\Auth\TwoFactorService;
use App\Services\V1\User\UserService;


class AuthService
{


    public function __construct(
        private TwoFactorService $twoFactor,
        private UserService $userService,
    ) {}

    private ?object $user = null;
    private string $userEmail;
    private ?object $request;
    private string $userPassword;
    private ?string $userToken = null;
    private ?array $twoFactorTokens = null;

    public function setInputs(LoginRequest $request): self
    {
        $this->userEmail = $request->email;
        $this->userPassword = $request->password;
        $this->request = $request;
        return $this;
    }

    public function userExist(): self
    {
        $user = $this->userService->getUserByEmail($this->userEmail);

        if (!$user) {
            throw new UserNotFoundException();
        }


        // BeforeLogin::new(true)
        //     ->setModel($user)
        //     ->handle();

        // if ($user->roles->pluck('name')->first() === 'employee') {
        //     $empData = $this->employeeInterface->getEmployeeCompanyProfileByUserId($user->id)->first();
        //     $user->setRelation('business', $empData?->employeeProfile?->business);
        // }
        $this->user = $user->first();

        return $this;
    }

    // public function hasRole(): self
    // {
    //     if (!$this->user || !$this->user->roles || $this->user->roles->isEmpty()) {
    //         throw new UserNotFoundException('User role not found.');
    //     }

    //     return $this;
    // }

    public function rateLimiter(): self
    {

        $key = sprintf('loginSignUp:%s|%s', strtolower($this->userEmail), $this->request->ip());

        if (RateLimiter::tooManyAttempts($key, 2)) {
            throw new TooManyLoginAttemptsException();
        }

        RateLimiter::hit($key, 60);

        return $this;
    }

    public function attemptAuth(): self
    {
        if (!Auth::attempt($this->request->only('email', 'password'))) {
            throw new AuthenticationFailedException();
        }

        $user = Auth::user();

        $this->twoFactorTokens = [$pendingToken, $expiresIn] = $this->twoFactor->startLogin($user, $this->request->ip(), $this->request->userAgent());
        return $this;
    }

    public function getAuthResponse(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'twoFactorTokens' => $this->twoFactorTokens[0],
        ]);
    }

    public function getUserData(): object
    {
        if (!$this->user) {
            throw new UserNotFoundException();
        }

        return $this->user;
    }

    // public function createUserAndCompany(array $data): object
    // {
    //     $user = $this->companyInterface->saveCompanyCreateSignup($data);

    //     notify()
    //         ->on('user_welcome')
    //         ->with($user)
    //         ->send(UserInvitationNotification::class);

    //     return $user;
    // }

    /**
     * Optional method to convert deeply nested array into Collection recursively.
     */
    public function deepCollect(array $array): Collection
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->deepCollect($value);
            }
        }

        return collect($array);
    }

    public function setInputsSignUp(SignupRequest $request): self
    {
        $this->userEmail = $request->email;
        $this->userPassword = $request->password;
        $this->request = $request;
        return $this;
    }

    public function sendOtpToken(): self
    {

        $signUpUserData = $this->request->only('name', 'nameArabic', 'email', 'password', 'termsAccepted');

        $this->twoFactorTokens = [$pendingToken, $expiresIn] = $this->twoFactor->startSignup($signUpUserData, $this->request->ip(), $this->request->userAgent());
        return $this;
    }

    // public function userAlreadyExists()
    // {
    //     $user = $this->userService->getUserByEmail($this->userEmail);
    //     echo '<pre>'; print_r($user->first()); echo '<pre/>';
    //      die();
    //     if ($user) {
    //         throw new UserFoundException();
    //     }

    //     return $this;
    // }

    public function getSignUpResponse(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'twoFactorTokens' => $this->twoFactorTokens[0],
        ]);
    }
}
