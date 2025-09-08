<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


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
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        } catch (TooManyLoginAttemptsException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 420);
        } catch (AuthenticationFailedException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 401);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 403);
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
            return response()->json(['success' => false, 'message' => $e->getMessage()], 404);
        } catch (TooManyLoginAttemptsException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 420);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 403);
        }
    }
}
