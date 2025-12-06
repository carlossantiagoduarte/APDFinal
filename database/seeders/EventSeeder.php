<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Admin',
            'lastname' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        Event::create([
            'user_id' => $user->id,
            'title' => 'HackaTec',
            'organizer' => 'CodeVision',
            'location' => 'Auditorio Principal ITO',
            'description' => 'Hackathon de tecnología abierta.',
            'email' => 'contacto@codevision.edu.mx',
            'phone' => '+52 951 123 4567',
            'max_participants' => 200,
            'requirements' => 'Laptop, identificación',
            'start_date' => now()->addDays(7)->toDateString(),
            'end_date' => now()->addDays(8)->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'image_url' => null,
            'documents_info' => null,
            'banner_url' => null,
            'modality' => 'Presencial',
            'registration_link' => null,
            'main_category' => 'Tecnología',
        ]);
    }
}
