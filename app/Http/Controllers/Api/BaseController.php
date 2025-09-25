<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    /**
     * Standard success response
     */
    protected function sendSuccessResponse(
        mixed $result = null,
        string $message = 'Success',
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Standard error response
     */
    protected function sendErrorResponse(
        mixed $errorDetails = null,
        string $error,
        int $statusCode = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorDetails)) {
            $response['errors'] = $errorDetails;
        }

        return response()->json($response, $statusCode);
    }
}
