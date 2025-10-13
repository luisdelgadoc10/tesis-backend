<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de permisos
        $permissions = [
            'view-dashboard',
            'view-profile',
            'view-users',
            'view-establecimientos',
            'view-clasificaciones',
            'view-funciones',
            'view-actividades',
            'view-roles',
            'view-permisos',
            'view-settings',
        ];

        // Crear permisos si no existen
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles
        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $fiscal = Role::firstOrCreate(['name' => 'Fiscalización']);
        $jefe = Role::firstOrCreate(['name' => 'Jefe Desarrollo Económico']);

        // Asignar TODOS los permisos a Admin y Jefe Desarrollo Económico
        $admin->syncPermissions($permissions);
        $jefe->syncPermissions($permissions);

        // Asignar permisos específicos a Fiscalización
        $fiscal->syncPermissions([
            'view-dashboard',
            'view-profile',
            'view-clasificaciones',
        ]);
    }
}
