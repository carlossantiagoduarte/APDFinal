<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Event;
use App\Models\User;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::first();
        $user  = User::where('email','estudiante@example.com')->first();

        if (!$event || !$user) return;

        Team::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'name' => 'Equipo Alfa',
            'leader_name' => 'Juan Pérez',
            'leader_email' => 'juan@example.com',
            'leader_career' => 'Ing. Sistemas',
            'leader_semester' => '7',
            'leader_experience' => 'Desarrollador con experiencia en proyectos web',
            'max_members' => 5,
            'visibility' => 'Público',
            'requirements' => 'Conocimientos básicos de programación',
            'invite_code' => 'ALFA123',
            'team_logo' => null,
            'description' => 'Equipo enfocado en soluciones IA',
            'skills_needed' => 'Python, IA, ML',
        ]);
    }
}
