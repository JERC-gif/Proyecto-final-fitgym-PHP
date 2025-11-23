<?php

/**
 * Controlador para gestionar membresías desde el perfil del usuario
 * 
 * Comando de creación:
 * php artisan make:controller Profile/MembresiaController
 * 
 * Funcionalidades:
 * - Ver membresía activa del usuario
 * - Adquirir/Asignar una nueva membresía
 * - Cancelar membresía activa
 */

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Membresia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MembresiaController extends Controller
{
    /**
     * Asigna/adquiere una membresía para el usuario autenticado
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adquirir(Request $request)
    {
        $user = Auth::user();
        
        // Validar que el usuario sea cliente
        if ($user->role !== 'client') {
            return redirect()->route('profile.show')
                ->with('error', 'Solo los clientes pueden adquirir membresías.');
        }

        // Validar que se haya seleccionado una membresía
        $request->validate([
            'membresia_id' => ['required', 'exists:membresias,id'],
        ]);

        $membresia = Membresia::findOrFail($request->membresia_id);

        // Verificar que la membresía esté activa
        if (!$membresia->activa) {
            return redirect()->route('profile.show')
                ->with('error', 'La membresía seleccionada no está disponible.');
        }

        // Verificar si el usuario ya tiene una membresía activa
        $membresiaActiva = DB::table('usuario_membresia')
            ->where('user_id', $user->id)
            ->where('activa', true)
            ->where('cancelada', false)
            ->whereDate('fecha_fin', '>=', now())
            ->first();

        if ($membresiaActiva) {
            return redirect()->route('profile.show')
                ->with('error', 'Ya tienes una membresía activa. Debes cancelarla antes de adquirir una nueva.');
        }

        // Calcular fechas
        $fechaInicio = now();
        $fechaFin = now()->addDays($membresia->duracion_dias);

        // Asignar la membresía al usuario
        DB::table('usuario_membresia')->insert([
            'user_id' => $user->id,
            'membresia_id' => $membresia->id,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'activa' => true,
            'cancelada' => false,
            'fecha_cancelacion' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('profile.show')
            ->with('success', "Membresía '{$membresia->nombre}' adquirida exitosamente. Válida hasta el " . $fechaFin->format('d/m/Y') . ".");
    }

    /**
     * Cancela la membresía activa del usuario autenticado
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelar()
    {
        $user = Auth::user();
        
        // Buscar la membresía activa del usuario
        $membresiaActiva = DB::table('usuario_membresia')
            ->where('user_id', $user->id)
            ->where('activa', true)
            ->where('cancelada', false)
            ->whereDate('fecha_fin', '>=', now())
            ->first();

        if (!$membresiaActiva) {
            return redirect()->route('profile.show')
                ->with('error', 'No tienes una membresía activa para cancelar.');
        }

        // Cancelar la membresía
        DB::table('usuario_membresia')
            ->where('id', $membresiaActiva->id)
            ->update([
                'activa' => false,
                'cancelada' => true,
                'fecha_cancelacion' => now(),
                'updated_at' => now(),
            ]);

        return redirect()->route('profile.show')
            ->with('success', 'Tu membresía ha sido cancelada exitosamente.');
    }
}
