<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

// ADD THIS: OpenAPI annotations namespace
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Mustaqel API",
 *     version="1.0.0"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */

abstract class Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}
