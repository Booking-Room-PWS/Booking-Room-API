<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
// use Throwable;
use App\Helpers\ApiFormatter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([ //* Tambahkan ini
            'is_admin' => \App\Http\Middleware\IsAdmin::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiFormatter::createJson(
                    405, 'Method Not Allowed',
                    [
                        'method' => $request->method(),
                        'allowed' => $e->getHeaders()['Allow'] ?? null,
                    ]
                );
            }
        });
        // Not Found -> JSON 404 for API routes
        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiFormatter::createJson(404, 'Resource Not Found');
            }
        });

        // Validation Exception -> JSON 422 with errors
        $exceptions->renderable(function (ValidationException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiFormatter::createJson(422, 'Validation Error', $e->errors());
            }
        });

        // Authentication Exception -> JSON 401
        $exceptions->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return ApiFormatter::createJson(401, 'Unauthenticated');
            }
        });

        // Generic fallback for API -> JSON 500 (safe message; debug info only when app.debug true)
        $exceptions->renderable(function (\Throwable $e, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $data = null;
                if (config('app.debug')) {
                    $data = [
                        'exception' => get_class($e),
                        'message' => $e->getMessage(),
                    ];
                }
                return ApiFormatter::createJson(500, 'Server Error', $data);
            }
        });
    })->create();