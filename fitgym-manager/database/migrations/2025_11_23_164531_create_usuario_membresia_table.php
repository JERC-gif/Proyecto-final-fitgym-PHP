<?php

/**
 * Migración para crear la tabla pivot usuario_membresia
 * 
 * Esta tabla relaciona usuarios (clientes) con sus membresías
 * 
 * Comando de creación:
 * php artisan make:migration create_usuario_membresia_table
 * 
 * Ejecutar migración:
 * php artisan migrate
 * 
 * Estructura de la tabla:
 * - id: Identificador único (auto-increment)
 * - user_id: ID del usuario (cliente)
 * - membresia_id: ID de la membresía
 * - fecha_inicio: Fecha de inicio de la membresía
 * - fecha_fin: Fecha de vencimiento de la membresía
 * - activa: Estado activo/inactivo (boolean, default: true)
 * - cancelada: Si fue cancelada por el usuario (boolean, default: false)
 * - fecha_cancelacion: Fecha de cancelación (nullable)
 * - timestamps: created_at, updated_at
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración: crea la tabla usuario_membresia
     */
    public function up(): void
    {
        Schema::create('usuario_membresia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('membresia_id')->constrained()->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activa')->default(true);
            $table->boolean('cancelada')->default(false);
            $table->timestamp('fecha_cancelacion')->nullable();
            $table->timestamps();
            
            // Índice único para evitar membresías duplicadas activas
            $table->unique(['user_id', 'membresia_id', 'activa'], 'unique_active_membership');
        });
    }

    /**
     * Revierte la migración: elimina la tabla usuario_membresia
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_membresia');
    }
};
