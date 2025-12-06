<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;

class TeamUserSeeder extends Seeder
{
    public function run(): void
    {
        $team = Team::first();
        $user = User::where('email','estudiante@example.com')->first();

        if ($team && $user) {
            $team->users()->attach($user->id, [
                'role' => 'member',
                'status' => 'accepted',
            ]);
        }
    }
}
