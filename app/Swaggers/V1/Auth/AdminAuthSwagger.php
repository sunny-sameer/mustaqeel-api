<?php

namespace App\Swaggers\V1\Auth;

use OpenApi\Annotations as OA;



/**
 * @OA\Tag(
 *     name="Admin Auth",
 *     description="All Admin Auth-related APIs (Login, Verify Token, Logout)"
 * )
 *
 *
 * @OA\Post(
 *     path="/api/v1/auth/admin/login",
 *     summary="Login an admin",
 *     tags={"Admin Auth"},
 *     @OA\Parameter(
 *         name="identifier",
 *         in="header",
 *         required=true,
 *         description="Admin identifier",
 *         @OA\Schema(
 *             type="string",
 *             example="jusour"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 example="superadmin@yopmail.com",
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 example="Jusour@2025",
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Verification Token",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *     )
 * )
 *
 *
 * @OA\Post(
 *     path="/api/v1/auth/admin/2fa/verify",
 *     summary="Verify OTP for login admin or validation",
 *     tags={"Admin Auth"},
 *     description="Verifies the 6-digit OTP sent to the admin's email with the pending token.",
 *     @OA\Parameter(
 *         name="identifier",
 *         in="header",
 *         required=true,
 *         description="Admin identifier",
 *         @OA\Schema(
 *             type="string",
 *             example="jusour"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "otp", "pendingToken"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 example="superadmin@yopmail.com",
 *             ),
 *             @OA\Property(
 *                 property="otp",
 *                 type="string",
 *                 example="548798",
 *             ),
 *             @OA\Property(
 *                 property="pendingToken",
 *                 type="string",
 *                 example="73065d870aa726adc2390feeb34b1c4c5b536498baaff69d2cc484ee783d6553",
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP verified successfully",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid OTP or expired token"
 *     )
 * )

 * @OA\Post(
 *     path="/api/v1/auth/admin/logout",
 *     summary="Logout a admin",
 *     tags={"Admin Auth"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(
 *         response=200,
 *         description="Logout",
 *     ),
 * )
 */

class AdminAuthSwagger
{
}
