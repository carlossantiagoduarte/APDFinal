<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------
        // 1. ADMIN (Ya lo tenías, lo dejo por si acaso)
        // ---------------------------------------------------
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        
        $admin = User::create([
            'name' => 'Admin User',
            'lastname' => 'Principal',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Columna BD (minúsculas)
        ]);

        $admin->assignRole($roleAdmin);
               $admin = User::create([
            'name' => 'Abraham',
            'lastname' => 'Cano',
            'email' => 'abraham@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Columna BD (minúsculas)
        ]);
        $admin->assignRole($roleAdmin);


        // ---------------------------------------------------
        // 2. JUEZ (Formato Nuevo)
        // ---------------------------------------------------
        // Aseguramos que el rol de Spatie exista
        $roleJuez = Role::firstOrCreate(['name' => 'Juez']);

        $juez = User::create([
            'name' => 'Juez Creador',
            'lastname' => 'Pérez', // Agregué apellido para completar tu tabla
            'email' => 'juez@example.com',
            'password' => Hash::make('password'),
            'role' => 'judge', // <--- IMPORTANTE: Debe coincidir con el enum de la migración ('judge')
        ]);
        // Asignamos el rol de Spatie
        $juez->assignRole($roleJuez);

        // ---------------------------------------------------
        // 3. ESTUDIANTE (Formato Nuevo)
        // ---------------------------------------------------
        // Aseguramos que el rol de Spatie exista
        $roleEstudiante = Role::firstOrCreate(['name' => 'Estudiante']);

        $estudiante = User::create([
            'name' => 'Estudiante Proy',
            'lastname' => 'López', // Agregué apellido
            'email' => 'estudiante@example.com',
            'password' => Hash::make('password'),
            'role' => 'student', // <--- IMPORTANTE: Debe coincidir con el enum de la migración ('student')
        ]);
        // Asignamos el rol de Spatie
        $estudiante->assignRole($roleEstudiante);

        // Mensaje final
        $this->command->info('Usuarios creados correctamente: Admin, Juez y Estudiante.');
    }
}