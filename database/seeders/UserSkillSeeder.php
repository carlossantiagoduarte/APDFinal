<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skill;

class UserSkillSeeder extends Seeder
{
    public function run(): void
    {
        $user  = User::where('email','estudiante@example.com')->first();
        $skill = Skill::first();

        if ($user && $skill) {
            $user->skills()->attach($skill->id);
        }
    }
}
