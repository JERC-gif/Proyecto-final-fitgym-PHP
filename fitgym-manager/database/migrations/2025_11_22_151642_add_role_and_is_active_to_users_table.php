<?php

/**
 * Migración para agregar campos role e is_active a la tabla users
 * 
 * Comando de creación:
 * php artisan make:migration add_role_and_is_active_to_users_table
 * 
 * Ejecutar migración:
 * php artisan migrate
 * 
 * Revertir migración:
 * php artisan migrate:rollback
 * 
 * Campos agregados:
 * - role: Rol del usuario (admin, staff, client) - default: 'client'
 * - is_active: Estado activo/inactivo - default: true
 * 
 * Ubicación: Después del campo 'email'
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración: agrega los campos role e is_active
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rol del usuario: admin, staff, client (default: client)
            $table->string('role')->default('client')->after('email');
            
            // Estado activo/inactivo (default: true)
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    /**
     * Revierte la migración: elimina los campos role e is_active
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
