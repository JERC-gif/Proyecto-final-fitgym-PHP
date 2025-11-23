<?php

/**
 * Acción de Fortify para crear nuevos usuarios durante el registro
 *
 * Esta clase se ejecuta cuando un usuario se registra desde el formulario público
 *
 * Configuración por defecto:
 * - role: 'client' (todos los usuarios registrados son clientes)
 * - is_active: true (usuarios activos por defecto)
 *
 * Nota: Los usuarios admin y staff deben crearse desde el panel administrativo
 */

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Valida y crea un nuevo usuario registrado
     *
     * Validaciones:
     * - Nombre: requerido, máximo 255 caracteres
     * - Email: requerido, formato válido, único en la tabla users
     * - Contraseña: según las reglas de Jetstream (mínimo 8 caracteres)
     * - Términos: requerido si Jetstream tiene habilitada la política de términos
     *
     * @param  array<string, string>  $input Datos del formulario de registro
     * @return User Usuario creado
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'client',      // Todos los usuarios registrados son clientes
            'is_active' => true,     // Usuarios activos por defecto
        ]);
    }
}
