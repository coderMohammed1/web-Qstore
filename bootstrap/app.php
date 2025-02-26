<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Validation\ValidationException;
use Bepsvpt\SecureHeaders\SecureHeadersMiddleware; // Import the Secure Headers middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Append Secure Headers Middleware
        $middleware->append(SecureHeadersMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $exception, Request $request) {
            // Check if request is for API
            if ($request->is('api/*')) {
                if ($exception instanceof NotFoundHttpException) {
                    return response()->json([
                        'error' => 'Resource not found'
                    ], 404);
                }

                if ($exception instanceof MethodNotAllowedHttpException) {
                    return response()->json([
                        'error' => 'Method Not Allowed'
                    ], 405);
                }

                if ($exception instanceof ValidationException) {
                    return response()->json([
                        'error' => 'Validation failed',
                        'messages' => $exception->errors()
                    ], 422);
                }

                return response()->json([
                    'error' => 'Server error',
                    'message' => $exception->getMessage()
                ], 500);
            }

            // For non-API routes, return the default error handling (HTML)
            return null;
        });
    })
    ->create();
