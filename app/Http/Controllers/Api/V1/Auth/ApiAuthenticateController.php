<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\API\V1\LoginRequest;
use App\Http\Requests\API\V1\SignupRequest;


use App\Services\V1\Auth\AuthService;
use App\Services\V1\User\UserService;


use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserFoundException;
use App\Exceptions\AuthenticationFailedException;
use App\Exceptions\TooManyLoginAttemptsException;





class ApiAuthenticateController extends BaseController
{
    /**
     * See Swagger annotations in \App\Swaggers\V1\Auth\AuthSwagger
    */


    private $authService;


    public function __construct(
        AuthService $authService,
        private UserService $UserService
    ) {

        $this->authService = $authService;
    }


    public function userLogin(LoginRequest $request): JsonResponse
    {
        try {
            return $this->authService
                ->setInputs($request)
                ->rateLimiter()
                ->userExist()
                ->attemptAuth()
                ->getAuthResponse();
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (TooManyLoginAttemptsException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 420);
        } catch (AuthenticationFailedException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 401);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }


    public function userSignUp(SignupRequest $request): JsonResponse
    {
        try {
            return $this->authService
                ->setInputsSignUp($request)
                ->rateLimiter()
                ->sendOtpToken()
                ->getSignUpResponse();
        } catch (UserFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (TooManyLoginAttemptsException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 420);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }
}
