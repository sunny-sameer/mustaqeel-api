<?php

namespace App\Swaggers\V1\Auth;

use OpenApi\Annotations as OA;



/**
 * @OA\Tag(
 *     name="Auth",
 *     description="All Auth-related APIs (Login, Verify Token, Signup, Reset, Logout)"
 * )
 *
 * @OA\Post(
 *     path="/api/v1/auth/login",
 *     summary="Login a user",
 *     tags={"Auth"},
 *     @OA\Parameter(
 *         name="identifier",
 *         in="header",
 *         required=true,
 *         description="User identifier",
 *         @OA\Schema(
 *             type="string",
 *             example="applicant"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 example="caspertalks@yopmail.com",
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
 *     path="/api/v1/auth/2fa/verify",
 *     tags={"Auth"},
 *     summary="Verify OTP for login or validation",
 *     description="Verifies the 6-digit OTP sent to the user's email with the pending token.",
 *     @OA\Parameter(
 *         name="identifier",
 *         in="header",
 *         required=true,
 *         description="User identifier",
 *         @OA\Schema(
 *             type="string",
 *             example="applicant"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "otp", "pendingToken"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 example="caspertalks@yopmail.com",
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
 *
 *
 * @OA\Post(
 *     path="/api/v1/auth/signup",
 *     tags={"Auth"},
 *     summary="Register a new user",
 *     description="Registers a new user after validating required data and agreement to terms.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password", "confirmPassword", "termsAccepted"},
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 example="Talha",
 *             ),
 *             @OA\Property(
 *                 property="nameArabic",
 *                 type="string",
 *                 example="طلحة",
 *             ),
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 example="talha@yopmail.com",
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 example="Qa@123123",
 *             ),
 *             @OA\Property(
 *                 property="confirmPassword",
 *                 type="string",
 *                 example="Qa@123123",
 *             ),
 *             @OA\Property(
 *                 property="termsAccepted",
 *                 type="boolean",
 *                 example=true,
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Verification Token",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/auth/reset",
 *     summary="Reset a user",
 *     tags={"Auth"},
 *     @OA\Parameter(
 *         name="identifier",
 *         in="header",
 *         required=true,
 *         description="User identifier",
 *         @OA\Schema(
 *             type="string",
 *             example="applicant"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 example="caspertalks@yopmail.com",
 *             ),
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
 *     path="/api/v1/auth/reset/password",
 *     tags={"Auth"},
 *     summary="Reset a user password",
 *     @OA\Parameter(
 *         name="identifier",
 *         in="header",
 *         required=true,
 *         description="User identifier",
 *         @OA\Schema(
 *             type="string",
 *             example="applicant"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "oldPassword", "password", "confirmPassword"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 example="caspertalks@yopmail.com",
 *             ),
 *             @OA\Property(
 *                 property="oldPassword",
 *                 type="string",
 *                 example="Xyz@123123",
 *             ),
 *             @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 example="Abc@123123",
 *             ),
 *             @OA\Property(
 *                 property="confirmPassword",
 *                 type="string",
 *                 example="Abc@123123",
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User password reset successfully",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 *
 *
 * @OA\Post(
 *     path="/api/v1/auth/logout",
 *     summary="Logout a user",
 *     tags={"Auth"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(
 *         response=200,
 *         description="Logout",
 *     ),
 * )
 *
 *
 * @OA\Post(
 *     path="/api/v1/auth/admin/login",
 *     summary="Login an admin",
 *     tags={"Auth"},
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
 *     tags={"Auth"},
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
 *     tags={"Auth"},
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(
 *         response=200,
 *         description="Logout",
 *     ),
 * )
 */

class AuthSwagger
{
}
