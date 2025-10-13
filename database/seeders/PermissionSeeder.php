<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'view-dashboard'],
            ['name' => 'view-profile'],
            ['name' => 'view-users'],
            ['name' => 'view-establecimientos'],
            ['name' => 'view-clasificaciones'],
            ['name' => 'view-funciones'],
            ['name' => 'view-actividades'],
            ['name' => 'view-roles'],
            ['name' => 'view-permisos'],
            ['name' => 'view-settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']]);
        }
    }
}
