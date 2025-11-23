<?php

/**
 * Migración para crear la tabla membresias
 *
 * Comando de creación:
 * php artisan make:migration create_membresias_table
 *
 * Ejecutar migración:
 * php artisan migrate
 *
 * Revertir migración:
 * php artisan migrate:rollback
 *
 * Estructura de la tabla:
 * - id: Identificador único (auto-increment)
 * - nombre: Tipo de membresía (Visita, Semana, Mes)
 * - descripcion: Descripción opcional (nullable)
 * - precio: Precio decimal con 2 decimales (10,2)
 * - duracion_dias: Duración en días (integer)
 * - activa: Estado activo/inactivo (boolean, default: true)
 * - timestamps: created_at, updated_at
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración: crea la tabla membresias
     */
    public function up(): void
    {
        Schema::create('membresias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');                    // Tipo: Visita, Semana, Mes
            $table->text('descripcion')->nullable();    // Descripción opcional
            $table->decimal('precio', 10, 2);          // Precio con 2 decimales
            $table->integer('duracion_dias');           // Duración en días
            $table->boolean('activa')->default(true);   // Estado activo por defecto
            $table->timestamps();                       // created_at, updated_at
        });
    }

    /**
     * Revierte la migración: elimina la tabla membresias
     */
    public function down(): void
    {
        Schema::dropIfExists('membresias');
    }
};
