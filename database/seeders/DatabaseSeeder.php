<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PermissionsSeeder; 

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $this->call([
        UserSeederPrueba::class,
        PermissionSeeder::class,
        RoleSeeder::class,
        UserSeeder::class,

        CategorySeeder::class,
        EventSeeder::class,

        TeamSeeder::class,
        TeamUserSeeder::class,

        SkillSeeder::class,
        UserSkillSeeder::class,
        UserInterestSeeder::class,
        DemoEvent3Seeder::class,
        EventCategorySeeder::class,
    ]);
}

}
