<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Api\BaseController;

use App\Http\Requests\API\V1\OtpVerifyRequest;

use App\Services\V1\Auth\TwoFactorService;
use App\Services\V1\User\UserService;


class TwoFactorController extends BaseController
{
    /**
     * See Swagger annotations in \App\Swaggers\V1\Auth\AuthSwagger
    */


    public function __construct(private TwoFactorService $twoFactor, private UserService $UserService) {}


    public function verify(OtpVerifyRequest $request): JsonResponse
    {
        $result = $this->twoFactor->verify(
            $request->pendingToken,
            $request->otp,
            $request->email,
            $request->ip(),
            $request->userAgent()
        );

        if (!$result->ok) {
            return $this->sendErrorResponse(false, $result->message, $result->status);
        }



        if (isset($result->flow) && $result->flow  == 'signup') {
            $userData = $result->suUser['signup_data'];

            $result = $this->UserService->createUser($userData, 'applicant');

            if ($result['success']) {
                return $this->sendSuccessResponse($result['data'], $result['message']);
            }

            return $this->sendErrorResponse($result['errors'], $result['message'], $result['status']);
        }


        if (isset($result->user) && $result->flow  == 'login') {
            $result = $this->UserService->createToken($result->user)->loginResponse();

            if ($result['success']) {
                return $this->sendSuccessResponse($result['data'], $result['message']);
            }

            return $this->sendErrorResponse($result['errors'], $result['message'], $result['status']);
        }

        return $this->sendErrorResponse('token verification', 'Unexpected error.', 500);
    }

    // public function resend(OtpResendRequest $request): JsonResponse
    // {
    //     $res = $this->twoFactor->resend($request->pending_token, $request->ip(), $request->userAgent());

    //     if (!$res->ok) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $res->message,
    //         ], $res->status);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'OTP resent.',
    //         'expires_in' => $res->expiresIn,
    //     ]);
    // }
}
