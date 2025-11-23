<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * 
     * Campos agregados:
     * - role: Rol del usuario (admin, staff, client)
     * - is_active: Estado activo/inactivo del usuario
     * 
     * Migración: database/migrations/xxxx_add_role_and_is_active_to_users_table.php
     * Comando: php artisan make:migration add_role_and_is_active_to_users_table
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // admin, staff, client
        'is_active',   // true/false
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Métodos helper para verificar el rol del usuario
     * 
     * Uso:
     * $user->isAdmin()  // true si es admin
     * $user->isStaff()  // true si es staff
     * $user->isClient() // true si es client
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Relación: Un usuario puede tener múltiples membresías
     * Tabla pivot: usuario_membresia
     */
    public function membresias()
    {
        return $this->belongsToMany(Membresia::class, 'usuario_membresia')
                    ->withPivot('fecha_inicio', 'fecha_fin', 'activa', 'cancelada', 'fecha_cancelacion')
                    ->withTimestamps();
    }

    /**
     * Obtiene la membresía activa del usuario
     * 
     * @return \App\Models\Membresia|null
     */
    public function membresiaActiva()
    {
        $membresiaPivot = DB::table('usuario_membresia')
            ->where('user_id', $this->id)
            ->where('activa', true)
            ->where('cancelada', false)
            ->whereDate('fecha_fin', '>=', now())
            ->first();

        if (!$membresiaPivot) {
            return null;
        }

        // Obtener el modelo Membresia
        $membresiaModel = \App\Models\Membresia::find($membresiaPivot->membresia_id);
        
        if ($membresiaModel) {
            // Agregar datos del pivot como atributo dinámico
            $membresiaModel->setAttribute('pivot_data', (object) [
                'fecha_inicio' => $membresiaPivot->fecha_inicio,
                'fecha_fin' => $membresiaPivot->fecha_fin,
                'activa' => (bool) $membresiaPivot->activa,
                'cancelada' => (bool) $membresiaPivot->cancelada,
                'fecha_cancelacion' => $membresiaPivot->fecha_cancelacion,
            ]);
        }

        return $membresiaModel;
    }
}
