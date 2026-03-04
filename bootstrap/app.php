<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            \App\Http\Middleware\TrimStrings::class,
            \App\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \Webkul\Core\Http\Middleware\SecureHeaders::class,
            \Webkul\Core\Http\Middleware\CheckForMaintenanceMode::class,
            \Webkul\Installer\Http\Middleware\CanInstall::class,
        ]);

        $middleware->alias([
            'auth'          => \Illuminate\Auth\Middleware\Authenticate::class,
            'auth.basic'    => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session'  => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can'           => \Illuminate\Auth\Middleware\Authorize::class,
            'guest'         => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'signed'        => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle'      => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $exception, $request) {
            if ($request->ajax()) {
                return response()->json([
                    'message'   => trans('admin::app.errors.413.title'),
                    'errorCode' => $exception->getStatusCode() ?? 413,
                ], $exception->getStatusCode() ?? 413);
            }

            return response()->view('admin::errors.index', ['errorCode' => $exception->getStatusCode() ?? 413]);
        });

        $exceptions->render(function (\Dotenv\Exception\InvalidFileException $exception, $request) {
            if ($request->ajax()) {
                return response()->json([
                    'message'   => $exception->getMessage(),
                ], 500);
            }

            exit($exception->getMessage());
        });
    })
    ->create();
