<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoEvent3Seeder extends Seeder
{
    public function run(): void
    {
        // 0. OBTENER UN USUARIO ADMINISTRADOR (Para asignar como creador del evento)
        // Si no existe ninguno, creamos uno temporal o fallará si la BD está vacía.
        $adminUser = User::first() ?? User::factory()->create();

        // 1. CREAR EL EVENTO
        $evento = Event::create([
            'user_id' => $adminUser->id, 
            'title' => 'Evento de Prueba #3 (QA)',
            'organizer' => 'QA Team',
            'location' => 'Laboratorio de Redes',
            'description' => 'Evento exclusivo para probar la inscripción de usuarios y equipos.',
            'email' => 'test@codevision.com',
            'phone' => '9998887777',
            'max_participants' => 70,
            'start_date' => '2025-11-20',
            'end_date' => '2025-11-21',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
            'main_category' => 'Testing',
            'modality' => 'Virtual',
            'is_active' => true,
        ]);

        // 2. CREAR AL USUARIO LÍDER DEL EQUIPO
        $lider = User::create([
            'name' => 'Líder Supremo',
            'lastname' => 'Test',
            'email' => 'lider@test.com',
            'password' => Hash::make('password'),
            'phone' => '1231231234',
            'role' => 'student',
        ]);

        // 3. CREAR EL EQUIPO (Vinculado al Evento y al Líder)
        $team = Team::create([
            'user_id' => $lider->id, // El dueño del registro del equipo
            'event_id' => $evento->id, // <--- CORRECCIÓN: Usamos el ID real del evento creado
            'name' => 'Los Testers Supremos',
            'leader_name' => $lider->name . ' ' . $lider->lastname,
            'leader_email' => $lider->email,
            'max_members' => 5,
            'visibility' => 'public',
            'invite_code' => 'TEST2025',
            'description' => 'Equipo generado automáticamente por Seeder',
        ]);

        // 4. AGREGAR AL LÍDER A LA TABLA PIVOTE (team_user)
        $team->users()->attach($lider->id, [
            'role' => 'leader',
            'status' => 'accepted'
        ]);

        // 5. CREAR 2 ESTUDIANTES MÁS Y AGREGARLOS COMO MIEMBROS
        for ($i = 1; $i <= 2; $i++) {
            $miembro = User::create([
                'name' => "Miembro Test $i",
                'lastname' => "Apellido $i",
                'email' => "miembro$i@test.com",
                'password' => Hash::make('password'),
                'phone' => '000000000' . $i,
                'role' => 'student',
            ]);

            // Agregar al equipo
            $team->users()->attach($miembro->id, [
                'role' => 'member',
                'status' => 'accepted' // Entran aceptados directamente
            ]);
        }

        $this->command->info('¡Éxito! Evento y Equipo "Los Testers Supremos" creados correctamente.');
    }
}