<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buscamos un usuario para que sea el creador (ej. ID 3, Estudiante)
        // Si no existe, usamos el ID 1 (Admin)
        $user = User::find(3) ?? User::find(1);

        // 2. Crear el Equipo
        $team = Team::create([
            'user_id' => $user->id,
            'event_id' => 1, // Asumiendo que el evento 1 existe
            'name' => 'Equipo Alfa',
            'leader_name' => $user->name . ' ' . $user->lastname, // Usamos datos reales del user
            'leader_email' => $user->email,
            'leader_career' => 'Ing. Sistemas',
            'leader_semester' => '7',
            'leader_experience' => 'Desarrollador Full Stack',
            'max_members' => 5,
            'visibility' => 'public', 
            'requirements' => 'Saber Laravel',
            'invite_code' => 'ALFA123',
            'team_logo' => 'images/default-team.png',
            'description' => 'Equipo de prueba para el sistema',
            'skills_needed' => 'PHP, SQL',
        ]);

        // 3. ¡AQUÍ ESTÁ EL CAMBIO IMPORTANTE!
        // En vez de insertar en 'team_members', UNIMOS al usuario en la tabla pivote 'team_user'
        
        // El líder se une automáticamente como 'accepted' y con rol de 'leader'
        $team->users()->attach($user->id, [
            'role' => 'leader',
            'status' => 'accepted'
        ]);

        // Opcional: Agregar otro miembro ficticio (si tienes más usuarios)
        $member = User::find(2); // Buscamos al Juez o a otro usuario
        if ($member) {
            $team->users()->attach($member->id, [
                'role' => 'member',
                'status' => 'pending' // Este simula que solicitó unirse
            ]);
        }
    }
}