<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    protected function sendSuccessResponse($result = NULL, $message = 'success', $statusCode = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];
        if (!empty($result)) {
            $response['response'] = $result;
        }

        return response()->json($response, $statusCode);
    }


    protected function sendErrorResponse($errorMessages = NULL, $error, $statusCode = Response::HTTP_BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['response'] = $errorMessages;
        }

        abort(response()->json($response, $statusCode));
    }
}
