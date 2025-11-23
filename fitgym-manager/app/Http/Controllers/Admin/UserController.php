<?php

/**
 * Controlador para la gestión de usuarios en el panel administrativo
 *
 * Comando de creación:
 * php artisan make:controller Admin/UserController --resource
 *
 * Funcionalidades:
 * - Listar usuarios (solo clientes)
 * - Crear nuevos usuarios con rol
 * - Editar usuarios (nombre, email, rol, estado, contraseña)
 * - Eliminar usuarios físicamente (no se pueden eliminar admins)
 *
 * Rutas: /admin/usuarios (protegidas con role:admin)
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Lista paginada de usuarios
     * Solo muestra usuarios con rol 'client' (excluye admin y staff)
     *
     * @return \Illuminate\View\View Vista con tabla de usuarios paginada
     */
    public function index()
    {
        // Filtrar solo usuarios clientes, ordenados por fecha de creación (más recientes primero)
        $users = User::where('role', 'client')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.usuarios.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario
     *
     * @return \Illuminate\View\View Vista del formulario de creación
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Guarda un nuevo usuario en la base de datos
     *
     * Validaciones:
     * - Nombre: requerido, máximo 255 caracteres
     * - Email: requerido, formato válido, único en la tabla users
     * - Contraseña: requerida, mínimo 8 caracteres, debe coincidir con confirmación
     * - Rol: solo permite 'client' o 'staff' (no se puede crear admin desde aquí)
     * - Estado activo: opcional (checkbox)
     *
     * @param Request $request Datos del formulario
     * @return \Illuminate\Http\RedirectResponse Redirige a la lista con mensaje de éxito
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['client', 'staff'])],
            'is_active' => ['boolean'],
        ]);

        // Crear usuario con email verificado automáticamente
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $request->has('is_active') ? true : false,
            'email_verified_at' => now(), // Verificar email automáticamente
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Muestra el formulario para editar un usuario existente
     *
     * Restricciones:
     * - No se pueden editar usuarios con rol 'admin' (error 403)
     *
     * @param string $id ID del usuario a editar
     * @return \Illuminate\View\View Vista del formulario de edición
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        // Solo permitir editar clientes y staff, no admins
        if ($user->role === 'admin') {
            abort(403, 'No se puede editar usuarios administradores.');
        }

        return view('admin.usuarios.edit', compact('user'));
    }

    /**
     * Actualiza los datos de un usuario existente
     *
     * Campos editables:
     * - Nombre
     * - Email (debe ser único, excepto el mismo usuario)
     * - Contraseña (opcional, solo se actualiza si se proporciona)
     * - Rol (solo 'client' o 'staff')
     * - Estado activo (is_active)
     *
     * @param Request $request Datos del formulario
     * @param string $id ID del usuario a actualizar
     * @return \Illuminate\Http\RedirectResponse Redirige a la lista con mensaje de éxito
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Solo permitir editar clientes y staff, no admins
        if ($user->role === 'admin') {
            abort(403, 'No se puede editar usuarios administradores.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['client', 'staff'])],
            'is_active' => ['boolean'],
        ]);

        // Actualizar campos
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->is_active = $request->has('is_active') ? true : false;

        // Solo actualizar contraseña si se proporciona una nueva
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Elimina físicamente un usuario de la base de datos
     *
     * Restricciones:
     * - No se pueden eliminar usuarios con rol 'admin' (error 403)
     * - La eliminación es permanente (no se puede deshacer)
     *
     * @param string $id ID del usuario a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirige a la lista con mensaje de éxito
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // No permitir borrar admins
        if ($user->role === 'admin') {
            abort(403, 'No se puede eliminar usuarios administradores.');
        }

        // Borrar físicamente de la base de datos
        $user->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }
}
