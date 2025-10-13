<?php
// bootstrap/app.php

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
        // ⚡ Configurar Sanctum para API
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // ⚡ SOLUCIÓN: Excluir rutas API del CSRF
        $middleware->validateCsrfTokens(except: [
            'api/*',  // Excluir TODAS las rutas API
            // O específicamente:
            // 'api/login',
            // 'api/register',
            // 'api/logout',
        ]);

        // ⚡ Configurar throttling para API (opcional)
        // $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();