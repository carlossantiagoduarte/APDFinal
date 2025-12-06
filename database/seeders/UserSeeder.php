<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear el usuario Admin y asignarle el rol
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        // 2. Crear el usuario Juez
        $juez = User::create([
            'name' => 'Juez Creador',
            'email' => 'juez@example.com',
            'password' => Hash::make('password'),
        ]);
        $juez->assignRole('Juez');

        // 3. Crear el usuario Estudiante
        $estudiante = User::create([
            'name' => 'Estudiante Proy',
            'email' => 'estudiante@example.com',
            'password' => Hash::make('password'),
        ]);
        $estudiante->assignRole('Estudiante');

        // Mensaje en consola
        $this->command->info('Usuarios creados con roles asignados: Admin, Juez, Estudiante (password: password).');
    }
}
