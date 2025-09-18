<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            // Generate a unique error reference ID
            $errorId = (string) Str::uuid();

            // Log the full exception with errorId
            Log::error("API Exception [{$errorId}]: " . $exception->getMessage(), [
                'exception' => $exception
            ]);

            // Validation errors
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $exception->errors(),
                    'error_id' => $errorId
                ], 422);
            }

            // Model not found
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Resource not found',
                    'error_id' => $errorId
                ], 404);
            }

            // Route not found
            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Endpoint not found',
                    'error_id' => $errorId
                ], 404);
            }

            // Wrong HTTP method
            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'HTTP method not allowed',
                    'error_id' => $errorId
                ], 405);
            }

            // Unauthorized
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'error_id' => $errorId
                ], 401);
            }

            // Default (unexpected errors)
            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error_id' => $errorId
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
