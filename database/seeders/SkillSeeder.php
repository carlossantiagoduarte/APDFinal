<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('skills')->insert([
            ['name' => 'Python'],
            ['name' => 'JavaScript'],
            ['name' => 'Laravel'],
            ['name' => 'Ciberseguridad'],
            ['name' => 'Machine Learning'],
        ]);
    }
}
