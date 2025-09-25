<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;

class MyBindingResolutionException extends GeneralException
{
    /**
     * Report the exception.
     */
    public function report(): bool
    {
        // Log custom information about the exception
        Log::error('Custom binding resolution error: ' . $this->getMessage());
        return false; // Return false to allow default reporting to continue if needed
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render($request)
    {
        // Return a custom response, e.g., a JSON response with a specific error code
        return response()->json([
            'status'  => false,
            'error'   => 'Custom Binding Resolution Error',
            'message' => $this->getMessage(),
        ], 500); // HTTP status code
    }
}
