<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Throwable;

class GeneralException extends Exception
{
    public function __construct($message = '', $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        // You can log here if needed
    }

    public function render($request)
    {
        $statusCode = ($this->getCode() >= 100 && $this->getCode() < 600) ? $this->getCode() : 500;

        return response()->json([
            'status' => false,
            'message' => $this->getMessage(),
        ], $statusCode);
    }
}
