<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::updateOrCreate(
            ['email' => 'admin@gym.test'],
            [
                'name' => 'Admin Gym',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // STAFF
        User::updateOrCreate(
            ['email' => 'staff@gym.test'],
            [
                'name' => 'Empleado Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => true,
            ]
        );

        // CLIENT
        User::updateOrCreate(
            ['email' => 'client@gym.test'],
            [
                'name' => 'Cliente Gym',
                'password' => Hash::make('password'),
                'role' => 'client',
                'is_active' => true,
            ]
        );
    }
}
