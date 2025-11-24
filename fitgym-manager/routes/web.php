<?php

/**
 * Archivo de rutas principales de la aplicación
 *
 * Estructura:
 * - Ruta raíz (/) con redirección inteligente según autenticación y rol
 * - Rutas protegidas con Jetstream (auth, verificación de email)
 * - Grupo admin (solo role:admin) - Panel y usuarios
 * - Grupo admin/staff (role:admin,staff) - Membresías
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/**
 * Ruta raíz: Redirección inteligente
 * - Si está autenticado como admin → Panel admin
 * - Si está autenticado como otro rol → Dashboard general
 * - Si no está autenticado → Login
 */
Route::get('/', function () {
    // Si está autenticado, redirigir según su rol
    if (Auth::check()) {
        $user = Auth::user();
        if ($user && ($user->role === 'admin' || $user->role === 'staff')) {
            return redirect()->route('admin.panel');
        }
        return redirect()->route('dashboard');
    }
    // Si no está autenticado, redirigir al login
    return redirect()->route('login');
});

/**
 * Rutas protegidas por Jetstream
 * Middleware aplicado:
 * - auth:sanctum: Requiere autenticación
 * - jetstream.auth_session: Sesión de Jetstream
 * - verified: Requiere email verificado
 */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    /**
     * Dashboard general
     * Accesible para cualquier usuario autenticado
     */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /**
     * Rutas para gestión de membresía desde el perfil
     * Solo para clientes
     */
    Route::prefix('profile')
        ->name('profile.')
        ->group(function () {
            // Adquirir/Asignar una membresía
            Route::post('/membresia/adquirir', [\App\Http\Controllers\Profile\MembresiaController::class, 'adquirir'])
                ->name('membresia.adquirir');

            // Cancelar membresía activa
            Route::post('/membresia/cancelar', [\App\Http\Controllers\Profile\MembresiaController::class, 'cancelar'])
                ->name('membresia.cancelar');
        });

    /**
     * Panel administrativo - Accesible para admin y staff
     * Prefijo: /admin
     * Middleware: role:admin,staff (admin y staff pueden acceder)
     */
    Route::prefix('admin')
        ->middleware(['role:admin,staff'])
        ->name('admin.')
        ->group(function () {

            // Panel principal (dashboard de estadísticas)
            Route::get('/panel', function () {
                // Estadísticas para el dashboard
                $totalUsuarios = \App\Models\User::where('role', 'client')->count();
                $totalMembresias = \App\Models\Membresia::count();
                $membresiasActivas = \App\Models\Membresia::where('activa', true)->count();
                $usuariosRecientes = \App\Models\User::where('role', 'client')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                $membresiasRecientes = \App\Models\Membresia::orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();

                return view('admin.dashboard', compact(
                    'totalUsuarios',
                    'totalMembresias',
                    'membresiasActivas',
                    'usuariosRecientes',
                    'membresiasRecientes'
                ));
            })->name('panel');

        });

    /**
     * Grupo de rutas administrativas - SOLO ADMIN
     * Prefijo: /admin
     * Middleware: role:admin (solo administradores)
     *
     * Rutas incluidas:
     * - Resource usuarios - CRUD completo de usuarios (solo clientes)
     */
    Route::prefix('admin')
        ->middleware('role:admin')
        ->name('admin.')
        ->group(function () {

            /**
             * Rutas resource para gestión de usuarios
             * Solo usuarios con rol 'client' se muestran y gestionan
             * SOLO ADMIN puede gestionar usuarios
             *
             * Rutas generadas:
             * GET    /admin/usuarios           - Listar usuarios
             * GET    /admin/usuarios/create    - Formulario crear
             * POST   /admin/usuarios           - Guardar nuevo usuario
             * GET    /admin/usuarios/{id}/edit - Formulario editar
             * PUT    /admin/usuarios/{id}      - Actualizar usuario
             * DELETE /admin/usuarios/{id}      - Eliminar usuario
             */
            Route::resource('usuarios', \App\Http\Controllers\Admin\UserController::class)
                ->names('usuarios');

        });

    /**
     * Grupo de rutas para membresías - SOLO LECTURA (Staff)
     * Prefijo: /admin
     * Middleware: role:admin,staff (admin y staff pueden ver)
     *
     * Rutas incluidas:
     * - GET /admin/membresias - Listar membresías (solo lectura para staff)
     * - GET /admin/usuario-membresias - Listar usuarios con sus membresías (admin y staff)
     */
    Route::prefix('admin')
        ->middleware(['role:admin,staff'])
        ->name('admin.')
        ->group(function () {

            // Staff solo puede ver la lista de membresías
            Route::get('/membresias', [\App\Http\Controllers\Admin\MembresiaController::class, 'index'])
                ->name('membresias.index');

            // Ver membresías de usuarios (admin y staff)
            Route::get('/usuario-membresias', [\App\Http\Controllers\Admin\UsuarioMembresiaController::class, 'index'])
                ->name('usuario-membresias.index');

        });

    /**
     * Grupo de rutas para membresías - CRUD COMPLETO (Solo Admin)
     * Prefijo: /admin
     * Middleware: role:admin (solo administradores)
     *
     * Rutas incluidas:
     * - Resource membresias - CRUD completo (crear, editar, eliminar)
     */
    Route::prefix('admin')
        ->middleware('role:admin')
        ->name('admin.')
        ->group(function () {

            /**
             * Rutas resource para gestión completa de membresías
             * SOLO ADMIN puede crear, editar y eliminar
             *
             * Tipos predefinidos:
             * - Visita: 1 día, $50
             * - Semana: 7 días, $250
             * - Mes: 30 días, $500
             *
             * Rutas generadas:
             * GET    /admin/membresias/create    - Formulario crear (solo admin)
             * POST   /admin/membresias           - Guardar nueva membresía (solo admin)
             * GET    /admin/membresias/{id}/edit - Formulario editar (solo admin)
             * PUT    /admin/membresias/{id}      - Actualizar membresía (solo admin)
             * DELETE /admin/membresias/{id}      - Eliminar membresía (solo admin)
             */
            Route::resource('membresias', \App\Http\Controllers\Admin\MembresiaController::class)
                ->except(['index']) // Excluir index porque ya está definido arriba
                ->names('membresias');

        });
});
