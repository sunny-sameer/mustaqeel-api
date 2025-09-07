<?php

namespace App\Http\Controllers\Api\V1\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\OtpVerifyRequest;
use App\Http\Requests\API\V1\OtpResendRequest;
use App\Services\V1\Auth\TwoFactorService;
use App\Services\V1\User\UserService;
use Illuminate\Http\JsonResponse;



class TwoFactorController extends Controller
{
    public function __construct(private TwoFactorService $twoFactor, private UserService $UserService) {}

    public function verify(OtpVerifyRequest $request): JsonResponse
    {

        $result = $this->twoFactor->verify(
            $request->pendingToken,
            $request->otp,
            $request->ip(),
            $request->userAgent()
        );

        if (!$result->ok) {
            return response()->json([
                'success' => false,
                'message' => $result->message,
            ], $result->status);
        }

        if (isset($result->user)) {
            $token = $result->user->createToken('api')->plainTextToken;

            return response()->json([
                'success' => true,
                'flow'    => 'login',
                'token'   => $token,
                'user'    => $result->user,
            ], 200);
        }

        if (isset($result->flow) && $result->flow  == 'signup') {
            // $user = \App\Models\User::create($result->signupData);

            // $token = $user->createToken('api')->plainTextToken;
            $this->UserService->createUser('entity');

            return response()->json([
                'success' => true,
                'flow'    => 'signup',
                'token'   => '$token',
                'user'    => '$user',
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unexpected error.',
        ], 500);
    }

    public function resend(OtpResendRequest $request): JsonResponse
    {
        $res = $this->twoFactor->resend($request->pending_token, $request->ip(), $request->userAgent());

        if (!$res->ok) {
            return response()->json([
                'success' => false,
                'message' => $res->message,
            ], $res->status);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP resent.',
            'expires_in' => $res->expiresIn,
        ]);
    }
}
