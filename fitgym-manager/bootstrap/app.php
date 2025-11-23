<?php

/**
 * Archivo de configuración principal de Laravel
 *
 * Este archivo configura:
 * - Rutas (web, api, console)
 * - Middleware personalizados
 * - Manejo de excepciones
 *
 * IMPORTANTE: Registro del middleware de roles
 * El middleware RoleMiddleware se registra aquí con el alias 'role'
 * para poder usarlo en las rutas como: ->middleware('role:admin')
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /**
         * Registro del middleware de roles
         *
         * Alias: 'role'
         * Uso en rutas:
         * ->middleware('role:admin')           // Solo admin
         * ->middleware('role:admin,staff')     // Admin o staff
         *
         * Ubicación del middleware: app/Http/Middleware/RoleMiddleware.php
         */
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
