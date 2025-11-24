<?php

/**
 * Migración para corregir la restricción única en usuario_membresia
 * 
 * Problema: La restricción única incluía el campo 'activa', lo que causaba
 * errores al cancelar membresías (cuando se actualizaba activa = false).
 * 
 * Solución: Eliminar la restricción antigua si existe y crear una nueva que solo
 * incluya user_id y membresia_id. La validación de membresías activas
 * se maneja en la aplicación.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar si existe el índice antiguo
        $oldIndex = DB::select("SHOW INDEXES FROM usuario_membresia WHERE Key_name = 'unique_active_membership'");
        
        if (!empty($oldIndex)) {
            // Eliminar el índice antiguo que incluía 'activa'
            DB::statement('ALTER TABLE usuario_membresia DROP INDEX unique_active_membership');
        }
        
        // Verificar si ya existe el nuevo índice
        $newIndex = DB::select("SHOW INDEXES FROM usuario_membresia WHERE Key_name = 'unique_user_membership'");
        
        if (empty($newIndex)) {
            // Crear la nueva restricción única sin 'activa'
            Schema::table('usuario_membresia', function (Blueprint $table) {
                $table->unique(['user_id', 'membresia_id'], 'unique_user_membership');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacer nada en el rollback, ya que la migración original
        // ya tiene la estructura correcta
    }
};
