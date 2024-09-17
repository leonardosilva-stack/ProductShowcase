<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        \Illuminate\Database\QueryException::class => 'error',
        \Symfony\Component\HttpKernel\Exception\HttpException::class => 'warning',
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Custom reporting logic
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            // Return JSON response for API requests
            return $this->handleApiException($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle API exceptions.
     *
     * @param \Throwable $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleApiException(Throwable $exception): JsonResponse
    {
        $status = 500;
        $message = 'Something went wrong';

        // Customize the status and message based on exception type
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $status = 404;
            $message = 'Resource not found';
        } elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
            $status = 422;
            $message = 'Validation error';
        }

        return response()->json([
            'error' => $message,
            'message' => $exception->getMessage(),
        ], $status);
    }
}
