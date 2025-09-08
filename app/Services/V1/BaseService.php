<?php

namespace App\Services\V1;

use Illuminate\Http\Response;

class BaseService
{
    /**
     * Return a consistent success payload
     */
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $statusCode = Response::HTTP_OK
    ): array {
        return [
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'status'  => $statusCode,
        ];
    }

    /**
     * Return a consistent error payload
     */
    protected function error(
        string $message,
        mixed $errors = null,
        int $statusCode = Response::HTTP_BAD_REQUEST
    ): array {
        return [
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
            'status'  => $statusCode,
        ];
    }
}
