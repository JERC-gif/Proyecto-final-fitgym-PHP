<?php

namespace Database\Seeders;

use App\Models\Membresia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembresiaUsuarioSeeder extends Seeder
{
    /**
     * Asigna membresías de prueba a usuarios clientes
     */
    public function run(): void
    {
        // Crear membresías de ejemplo si no existen
        $membresiaVisita = Membresia::firstOrCreate(
            ['nombre' => 'Visita'],
            [
                'descripcion' => 'Acceso por un día',
                'precio' => 50.00,
                'duracion_dias' => 1,
                'activa' => true,
            ]
        );

        $membresiaSemana = Membresia::firstOrCreate(
            ['nombre' => 'Semana'],
            [
                'descripcion' => 'Acceso por una semana',
                'precio' => 250.00,
                'duracion_dias' => 7,
                'activa' => true,
            ]
        );

        $membresiaMes = Membresia::firstOrCreate(
            ['nombre' => 'Mes'],
            [
                'descripcion' => 'Acceso por un mes completo',
                'precio' => 500.00,
                'duracion_dias' => 30,
                'activa' => true,
            ]
        );

        // Asignar membresías a usuarios clientes de prueba
        $clientes = User::where('role', 'client')->get();

        foreach ($clientes as $index => $cliente) {
            // Asignar diferentes membresías a diferentes clientes
            $membresia = null;
            if ($index % 3 === 0) {
                $membresia = $membresiaMes;
            } elseif ($index % 3 === 1) {
                $membresia = $membresiaSemana;
            } else {
                $membresia = $membresiaVisita;
            }

            // Verificar si el usuario ya tiene una membresía activa
            $tieneMembresia = DB::table('usuario_membresia')
                ->where('user_id', $cliente->id)
                ->where('activa', true)
                ->where('cancelada', false)
                ->exists();

            if (!$tieneMembresia) {
                $fechaInicio = now();
                $fechaFin = now()->addDays($membresia->duracion_dias);

                DB::table('usuario_membresia')->insert([
                    'user_id' => $cliente->id,
                    'membresia_id' => $membresia->id,
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'activa' => true,
                    'cancelada' => false,
                    'fecha_cancelacion' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
