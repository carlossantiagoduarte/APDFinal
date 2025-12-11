<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeederPrueba extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Encriptamos la contraseña una sola vez para optimizar la velocidad
        $password = Hash::make('password');

        // ------------------------------------------------
        // 1. CREAR EL ADMIN PRINCIPAL
        // ------------------------------------------------
        User::create([
            'name' => 'Admin Principal',
            'lastname' => 'Sistema',
            'email' => 'admin@codevision.com',
            'phone' => '0000000000',
            'password' => $password,
            'role' => 'admin',
        ]);

        // ------------------------------------------------
        // 2. CREAR 10 JUECES
        // ------------------------------------------------
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => 'Juez ' . $i,
                'lastname' => 'Calificador',
                'email' => 'juez' . $i . '@prueba.com',
                'phone' => '55511122' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'password' => $password,
                'role' => 'judge', 
            ]);
        }

        // ------------------------------------------------
        // 3. CREAR 20 ESTUDIANTES
        // ------------------------------------------------
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => 'Estudiante ' . $i,
                'lastname' => 'Test',
                'email' => 'estudiante' . $i . '@prueba.com',
                'phone' => '55599988' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'password' => $password,
                'role' => 'student', 
            ]);
        }
    }
}