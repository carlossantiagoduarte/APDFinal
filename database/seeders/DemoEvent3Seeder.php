<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DemoEvent3Seeder extends Seeder
{
    public function run(): void
    {
        // 1. CREAR EL EVENTO #3
        // Usamos 'updateOrCreate' por si corres el seeder dos veces no se duplique
        $evento = Event::updateOrCreate(
            ['id' => 3], // Forzamos que sea el ID 3
            [
                'user_id' => 1, // El admin es el creador
                'title' => 'Evento de Prueba #3',
                'organizer' => 'QA Team',
                'location' => 'Laboratorio de Redes',
                'description' => 'Evento exclusivo para probar la inscripción de usuarios.',
                'email' => 'test@codevision.com',
                'phone' => '9998887777',
                'max_participants' => 50,
                'start_date' => '2025-11-20',
                'end_date' => '2025-11-21',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'main_category' => 'Testing',
                'modality' => 'Virtual',
                'is_active' => true,
            ]
        );

        // 2. CREAR UN EQUIPO EN ESE EVENTO
        $team = Team::create([
            'user_id' => 1, // Temporalmente lo crea el admin o un user X
            'event_id' => 3, // <--- IMPORTANTE: Pertenece al Evento 3
            'name' => 'Los Testers Supremos',
            'leader_name' => 'Líder Test',
            'leader_email' => 'lider@test.com',
            'max_members' => 5,
            'visibility' => 'public',
            'invite_code' => 'TEST2025',
            'description' => 'Equipo generado automáticamente',
        ]);

        // 3. CREAR 3 ESTUDIANTES Y METERLOS AL EQUIPO
        $roleEstudiante = Role::firstOrCreate(['name' => 'Estudiante']);

        for ($i = 1; $i <= 3; $i++) {
            // Crear usuario
            $user = User::create([
                'name' => "Estudiante Test $i",
                'lastname' => "Apellido $i",
                'email' => "test$i@evento3.com",
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
            
            // Asignar rol de Spatie
            $user->assignRole($roleEstudiante);

            // 4. METER AL EQUIPO (Tabla Pivote)
            $team->users()->attach($user->id, [
                'role' => ($i === 1) ? 'leader' : 'member', // El primero es líder
                'status' => 'accepted' // Ya entran aceptados
            ]);
        }

        $this->command->info('¡Éxito! Evento 3 creado con 1 equipo y 3 estudiantes inscritos.');
    }
}