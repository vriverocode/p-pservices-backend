<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Superadmin', 'description' => 'Super Admin', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Admin', 'description' => 'Administrador del Sistema', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Client', 'description' => 'Cliente del Sistema', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('roles')->insertOrIgnore($roles);
    }
}
