<?php

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, $request) {
            
            if ($request->is('api/*') ||$request->expectsJson()) {
                // Handle authentication error (unauthenticated)
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return ApiResponse::error('Unauthenticated', 401);
                }
           

                // Handle authorization error (unauthorized)
                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    return ApiResponse::error('Unauthorized', 403);

                }

                // Handle HTTP exceptions (like 404, 405, etc)
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    return ApiResponse::error($e->getMessage() ?: 'HTTP Error',$e->getStatusCode()??500);

                }

                // Fallback for unhandled errors
                return response()->json([
                    'message' => 'Server Error',
                    'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong',
                ], 500);
            }

            // Default Laravel behavior for web routes (redirects, etc.)
            return null;
        });
    })->create();
