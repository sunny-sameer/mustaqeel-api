<?php

namespace App\Http\Controllers\Api\V1\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\OtpVerifyRequest;
use App\Http\Requests\API\V1\OtpResendRequest;
use App\Services\V1\Auth\TwoFactorService;
use Illuminate\Http\JsonResponse;



class TwoFactorController extends Controller
{
    public function __construct(private TwoFactorService $twoFactor) {}

    public function verify(OtpVerifyRequest $request): JsonResponse
    {

        $result = $this->twoFactor->verify(
            $request->pending_token,
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

        $token = $result->user->createToken('api')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
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
