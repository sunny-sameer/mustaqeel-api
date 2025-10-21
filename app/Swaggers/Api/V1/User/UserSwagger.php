<?php

namespace App\Swaggers\Api\V1\User;

use OpenApi\Annotations as OA;



/**
 * @OA\Tag(
 *     name="User",
 *     description="All User-related APIs (Resolver)"
 * )
 *
 * @OA\Get(
 *     path="/api/v1/user/resolve",
 *     tags={"User"},
 *     summary="Get user resolver",
 *     security={{ "bearerAuth": {} }},
 *     @OA\Response(response=200, description="OK")
 * )
 */

class UserSwagger
{
}
