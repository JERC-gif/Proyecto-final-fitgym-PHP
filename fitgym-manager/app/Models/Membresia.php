<?php

/**
 * Modelo Eloquent para la tabla membresias
 * 
 * Comando de creación:
 * php artisan make:model Membresia -m
 * 
 * Migración: database/migrations/xxxx_create_membresias_table.php
 * 
 * Campos:
 * - nombre: Tipo de membresía (Visita, Semana, Mes)
 * - descripcion: Descripción opcional de la membresía
 * - precio: Precio en decimal (2 decimales)
 * - duracion_dias: Duración en días (1, 7, 30 según tipo)
 * - activa: Estado activo/inactivo (boolean)
 * 
 * Tipos predefinidos:
 * - Visita: 1 día, $50
 * - Semana: 7 días, $250
 * - Mes: 30 días, $500
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'duracion_dias',
        'activa',
    ];

    /**
     * Conversiones de tipos de datos
     */
    protected $casts = [
        'precio' => 'decimal:2',        // Precio con 2 decimales
        'duracion_dias' => 'integer',    // Duración como entero
        'activa' => 'boolean',           // Estado como booleano
    ];

    /**
     * Relación: Una membresía puede pertenecer a múltiples usuarios
     * Tabla pivot: usuario_membresia
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuario_membresia')
                    ->withPivot('fecha_inicio', 'fecha_fin', 'activa', 'cancelada', 'fecha_cancelacion')
                    ->withTimestamps();
    }
}
