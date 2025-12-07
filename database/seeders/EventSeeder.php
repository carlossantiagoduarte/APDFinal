<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::create([
            'user_id' => 1, // Asignado al primer usuario (Admin)
            'title' => 'HackaTec 2025',
            'organizer' => 'CodeVision Team',
            'location' => 'Auditorio Principal ITO',
            'description' => 'El hackathon más grande de tecnología.',
            'email' => 'contacto@codevision.com',
            'phone' => '9511234567',
            'max_participants' => 200,
            'requirements' => 'Traer laptop e identificación.',
            'start_date' => '2025-12-14',
            'end_date' => '2025-12-15',
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'modality' => 'Presencial',
            'main_category' => 'Tecnología',
            'is_active' => true,
        ]);

        Event::create([
            'user_id' => 1,
            'title' => 'Concurso de Algoritmia',
            'organizer' => 'Academia de Sistemas',
            'location' => 'Laboratorio 3',
            'description' => 'Resolver problemas complejos en C++ o Java.',
            'email' => 'sistemas@ito.edu.mx',
            'start_date' => '2026-06-20',
            'start_time' => '10:00:00',
            'modality' => 'Híbrido',
            'main_category' => 'Programación',
            'is_active' => true,
        ]);
    }
}