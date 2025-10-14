<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'view-dashboard',
                'descripcion' => 'Acceso al panel principal',
            ],
            [
                'name' => 'view-users',
                'descripcion' => 'Gestión de usuarios del sistema',
            ],
            [
                'name' => 'view-establecimientos',
                'descripcion' => 'Administración de establecimientos registrados',
            ],
            [
                'name' => 'view-clasificaciones',
                'descripcion' => 'Gestión de clasificaciones de riesgo',
            ],
            [
                'name' => 'view-mapa-riesgo',
                'descripcion' => 'Visualización del mapa de riesgo',
            ],
            [
                'name' => 'view-funciones',
                'descripcion' => 'Administración de funciones principales',
            ],
            [
                'name' => 'view-subfunciones',
                'descripcion' => 'Gestión de subfunciones dependientes',
            ],
            [
                'name' => 'view-actividades',
                'descripcion' => 'Gestión de actividades económicas',
            ],
            [
                'name' => 'view-roles',
                'descripcion' => 'Administración de roles de usuario',
            ],
            [
                'name' => 'view-permisos',
                'descripcion' => 'Gestión de permisos del sistema',
            ],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm['name']],
                [
                    'guard_name' => 'web',
                    'descripcion' => $perm['descripcion'],
                    'estado' => 1, // ✅ solo si tienes esta columna en la tabla
                ]
            );
        }
    }
}
