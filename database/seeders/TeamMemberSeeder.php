<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeamMember;
use App\Models\Team;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $team = Team::first();
        if (!$team) return;

        TeamMember::create([
            'team_id' => $team->id,
            'name' => 'Carlos López',
            'email' => 'carlos@example.com',
            'career' => 'Ing. Informática',
            'phone' => '+52 951 000 1111',
            'role' => 'member',
        ]);
    }
}
