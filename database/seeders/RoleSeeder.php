<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $admin  = Role::firstOrCreate(['name' => 'Admin']);
        $juez   = Role::firstOrCreate(['name' => 'Juez']);
        $estu   = Role::firstOrCreate(['name' => 'Estudiante']);

        // Asignar permisos
        $admin->syncPermissions(Permission::all());

        $juez->syncPermissions([
            'ver eventos',
            'calificar',
        ]);

        $estu->syncPermissions([
            'ver eventos',
            'enviar proyecto',
        ]);
    }
}
