<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Insertar roles estáticos (insertOrIgnore previene duplicados)
        $roles = [
            'Superadmin' => ['name' => 'Superadmin', 'description' => 'Super Admin', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            'Admin' => ['name' => 'Admin', 'description' => 'Administrador del Sistema', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            'Client' => ['name' => 'Client', 'description' => 'Cliente del Sistema', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('roles')->insertOrIgnore($roles);

        // 2. Obtener los IDs de los roles insertados
        // Se hace una sola consulta y se indexa por nombre para evitar múltiples queries a la BD.
        $rolesDb = DB::table('roles')
            ->whereIn('name', ['Superadmin', 'Admin', 'Client'])
            ->get()
            ->keyBy('name');


        $testUsers = [
            [
                'name' => 'Superadmin User',
                'email' => 'superadmin@prueba.com',
                'rol_id' => $rolesDb['Superadmin']->id,
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@prueba.com',
                'rol_id' => $rolesDb['Admin']->id,
            ],
            [
                'name' => 'Client User',
                'email' => 'client@prueba.com',
                'rol_id' => $rolesDb['Client']->id,
            ],
        ];

        // 4. Inserción segura disparando eventos de Eloquent (Traits/ULID)
        foreach ($testUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']], // Condición de búsqueda
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('12345678'), // Contraseña del 1 al 8
                    'rol_id' => $userData['rol_id'],
                    'email_verified_at' => now(), // Bypass de verificación para desarrollo
                ]
            );
        }
    }
}
