<?php

/**
 * Middleware para verificar roles de usuario
 * 
 * Registrado en: bootstrap/app.php
 * Alias: 'role'
 * 
 * Uso en rutas:
 * ->middleware('role:admin')           // Solo admin
 * ->middleware('role:admin,staff')     // Admin o staff
 * 
 * Funcionalidad:
 * - Verifica que el usuario esté autenticado
 * - Comprueba que el rol del usuario esté en la lista de roles permitidos
 * - Soporta múltiples roles separados por comas
 * - Retorna error 403 si no tiene permisos
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Maneja la solicitud y verifica el rol del usuario
     * 
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles Roles permitidos (pueden ser múltiples separados por comas)
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Verificar que el usuario esté autenticado
        if (!$user) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        // Manejar múltiples roles separados por comas (ej: role:admin,staff)
        $allowedRoles = [];
        foreach ($roles as $role) {
            // Si el rol contiene comas, separarlo
            if (str_contains($role, ',')) {
                $allowedRoles = array_merge($allowedRoles, array_map('trim', explode(',', $role)));
            } else {
                $allowedRoles[] = trim($role);
            }
        }

        // Si el rol del usuario no está en la lista permitida → 403
        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
