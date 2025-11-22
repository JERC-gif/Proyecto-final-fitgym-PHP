<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Si está autenticado, redirigir según su rol
    if (Auth::check()) {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.panel');
        }
        return redirect()->route('dashboard');
    }
    // Si no está autenticado, redirigir al login
    return redirect()->route('login');
});

// Rutas protegidas por Jetstream (usuarios logueados y verificados)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard normal (cualquiera logueado)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Grupo admin
    Route::prefix('admin')
        ->middleware('role:admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/panel', function () {
                return view('admin.dashboard');
            })->name('panel');

        });
});
