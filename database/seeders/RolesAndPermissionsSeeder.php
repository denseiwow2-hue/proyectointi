<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear roles y permisos en caché
        app()['cache']->forget('spatie.permission.cache');

        $permissions = [
            'ver tareas',
            'crear tareas',
            'editar tareas',
            'eliminar tareas',
            'subir imagen',
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'gestionar roles',
            'ver reportes',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        $encargadoRole = Role::firstOrCreate(['name' => 'encargado']);
        $encargadoRole->syncPermissions(array_diff($permissions, ['gestionar roles']));

        $userRole = Role::firstOrCreate(['name' => 'usuario']);
        $userRole->syncPermissions([
            'ver tareas',
            'subir imagen',
        ]);
    }
}
