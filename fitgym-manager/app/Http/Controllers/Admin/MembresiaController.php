<?php

/**
 * Controlador para la gestión de membresías en el panel administrativo
 *
 * Comando de creación:
 * php artisan make:controller Admin/MembresiaController --resource
 *
 * Funcionalidades:
 * - Listar membresías paginadas
 * - Crear nuevas membresías (Visita, Semana, Mes con precios/días predefinidos)
 * - Editar membresías (nombre, descripción, precio, duración, estado)
 * - Eliminar membresías físicamente
 *
 * Rutas: /admin/membresias (protegidas con role:admin,staff)
 * Acceso: Admin y Staff pueden gestionar membresías
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membresia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembresiaController extends Controller
{
    /**
     * Lista paginada de todas las membresías
     * Ordenadas por fecha de creación (más recientes primero)
     *
     * @return \Illuminate\View\View Vista con tabla de membresías paginada
     */
    public function index()
    {
        $membresias = Membresia::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.membresias.index', compact('membresias'));
    }

    /**
     * Muestra el formulario para crear una nueva membresía
     * SOLO ADMIN puede acceder (protegido por middleware)
     */
    public function create()
    {
        // Verificación adicional: solo admin puede crear
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Solo los administradores pueden crear membresías.');
        }

        return view('admin.membresias.create');
    }

    /**
     * Guarda una nueva membresía en la base de datos
     * SOLO ADMIN puede crear (protegido por middleware)
     *
     * Tipos predefinidos (seleccionados desde el formulario):
     * - Visita: 1 día, $50
     * - Semana: 7 días, $250
     * - Mes: 30 días, $500
     *
     * Validaciones:
     * - Nombre: requerido, máximo 255 caracteres
     * - Descripción: opcional
     * - Precio: requerido, numérico, mínimo 0
     * - Duración días: requerido, entero, mínimo 1
     * - Estado activa: opcional (checkbox)
     *
     * @param Request $request Datos del formulario
     * @return \Illuminate\Http\RedirectResponse Redirige a la lista con mensaje de éxito
     */
    public function store(Request $request)
    {
        // Verificación adicional: solo admin puede crear
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Solo los administradores pueden crear membresías.');
        }
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'duracion_dias' => ['required', 'integer', 'min:1'],
            'activa' => ['boolean'],
        ]);

        Membresia::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'precio' => $validated['precio'],
            'duracion_dias' => $validated['duracion_dias'],
            'activa' => $request->has('activa') ? true : false,
        ]);

        return redirect()->route('admin.membresias.index')
            ->with('success', 'Membresía creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Muestra el formulario para editar una membresía
     * SOLO ADMIN puede editar (protegido por middleware)
     */
    public function edit(string $id)
    {
        // Verificación adicional: solo admin puede editar
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Solo los administradores pueden editar membresías.');
        }

        $membresia = Membresia::findOrFail($id);

        return view('admin.membresias.edit', compact('membresia'));
    }

    /**
     * Actualiza una membresía existente
     * SOLO ADMIN puede actualizar (protegido por middleware)
     */
    public function update(Request $request, string $id)
    {
        // Verificación adicional: solo admin puede actualizar
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Solo los administradores pueden actualizar membresías.');
        }

        $membresia = Membresia::findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'duracion_dias' => ['required', 'integer', 'min:1'],
            'activa' => ['boolean'],
        ]);

        $membresia->update([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'precio' => $validated['precio'],
            'duracion_dias' => $validated['duracion_dias'],
            'activa' => $request->has('activa') ? true : false,
        ]);

        return redirect()->route('admin.membresias.index')
            ->with('success', 'Membresía actualizada exitosamente.');
    }

    /**
     * Elimina físicamente una membresía de la base de datos
     * SOLO ADMIN puede eliminar (protegido por middleware)
     *
     * La eliminación es permanente (no se puede deshacer)
     *
     * @param string $id ID de la membresía a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirige a la lista con mensaje de éxito
     */
    public function destroy(string $id)
    {
        // Verificación adicional: solo admin puede eliminar
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Solo los administradores pueden eliminar membresías.');
        }

        $membresia = Membresia::findOrFail($id);

        // Borrar físicamente de la base de datos
        $membresia->delete();

        return redirect()->route('admin.membresias.index')
            ->with('success', 'Membresía eliminada exitosamente.');
    }
}
