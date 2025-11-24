<?php

/**
 * Controlador para gestionar las membresías de los usuarios
 *
 * Comando de creación:
 * php artisan make:controller Admin/UsuarioMembresiaController
 *
 * Funcionalidades:
 * - Listar usuarios con sus membresías asignadas
 * - Ver detalles de membresías de cada usuario
 *
 * Acceso: Admin y Staff pueden ver las membresías de usuarios
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioMembresiaController extends Controller
{
    /**
     * Lista todos los usuarios (clientes) con sus membresías asignadas
     *
     * @return \Illuminate\View\View Vista con tabla de usuarios y sus membresías
     */
    public function index()
    {
        // Obtener usuarios clientes paginados
        $users = User::where('role', 'client')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Obtener IDs de usuarios para la consulta
        $userIds = $users->pluck('id');

        // Obtener todas las membresías de los usuarios en una sola consulta
        $membresiasDetalle = DB::table('usuario_membresia')
            ->whereIn('user_id', $userIds)
            ->join('membresias', 'usuario_membresia.membresia_id', '=', 'membresias.id')
            ->select(
                'usuario_membresia.*',
                'membresias.nombre',
                'membresias.precio',
                'membresias.duracion_dias',
                'membresias.descripcion'
            )
            ->orderBy('usuario_membresia.created_at', 'desc')
            ->get()
            ->groupBy('user_id');

        // Asignar membresías a cada usuario
        foreach ($users as $user) {
            $user->membresias_detalle = $membresiasDetalle->get($user->id, collect());
        }

        return view('admin.usuario-membresia.index', compact('users'));
    }
}
