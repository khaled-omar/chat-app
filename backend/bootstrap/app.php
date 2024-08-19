<?php

use App\Http\Middleware\Authorization;
use App\Http\Middleware\AuthorizeClient;
use App\Http\Middleware\IsCompanyProfileCompleted;
use App\Http\Middleware\Localization;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', [
            Localization::class,
            AuthorizeClient::class,
        ]);

        $middleware->alias([
            'authorize' => Authorization::class,
            'completed' => IsCompanyProfileCompleted::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exception $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->api($e);
            }
        });

    })->create();
