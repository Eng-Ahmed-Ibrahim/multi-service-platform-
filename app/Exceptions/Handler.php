<?php

namespace App\Exceptions;

use Throwable;
use App\Exceptions\CustomValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */

public function render($request, Throwable $exception)
{
    if ($exception instanceof CustomValidationException) {
        return response()->json([
            'status' => $exception->getStatusCode(),
            'msg' => $exception->getMessage(),
            'data' => null,
        ], $exception->getStatusCode());
    }

    return parent::render($request, $exception);
}
}
