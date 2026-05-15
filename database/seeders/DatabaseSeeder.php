<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar el seeder de roles y permisos primero
        $this->call(RolesAndPermissionsSeeder::class);

        // Crear usuario ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'ci' => '12345678',
                'bloque' => 'A',
                'password' => User::factory()->make()->password,
            ]
        );
        $admin->assignRole('admin');

        // Crear usuario ENCARGADO
        $encargado = User::firstOrCreate(
            ['email' => 'encargado@example.com'],
            [
                'name' => 'Encargado General',
                'ci' => '87654321',
                'bloque' => 'B',
                'password' => User::factory()->make()->password,
            ]
        );
        $encargado->assignRole('encargado');

        // Crear usuario normal
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Usuario Normal',
                'ci' => '11223344',
                'bloque' => 'C',
                'password' => User::factory()->make()->password,
            ]
        );
        $user->assignRole('usuario');
    }
}
