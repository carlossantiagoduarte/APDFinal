<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Inteligencia Artificial'],
            ['name' => 'Desarrollo Web'],
            ['name' => 'Ciberseguridad'],
            ['name' => 'Datos / Machine Learning'],
            ['name' => 'Emprendimiento'],
        ];

        DB::table('categories')->insert($categories);
    }
}
