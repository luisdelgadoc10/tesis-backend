<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarnos de que los roles existen
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $fiscalRole = Role::firstOrCreate(['name' => 'Fiscalización']);

        // Usuario con ID 1 -> Administrador
        $user1 = User::find(1);
        if ($user1) {
            $user1->syncRoles([$adminRole]);
        }

        // Usuario con ID 2 -> Fiscalización
        $user2 = User::find(2);
        if ($user2) {
            $user2->syncRoles([$fiscalRole]);
        }
    }
}
