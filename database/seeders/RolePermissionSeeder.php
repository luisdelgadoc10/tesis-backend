<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Permission; // 👈 usamos tu modelo extendido con descripcion y estado

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔹 Obtener todos los permisos creados en PermissionSeeder
        $permissions = Permission::all();

        // 🔹 Crear roles si no existen
        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $fiscal = Role::firstOrCreate(['name' => 'Fiscalización']);
        $jefe = Role::firstOrCreate(['name' => 'Jefe Desarrollo Económico']);

        // 🔹 Asignar TODOS los permisos al Administrador
        $admin->syncPermissions($permissions);

        // 🔹 Asignar los mismos permisos al Jefe (opcional)
        $jefe->syncPermissions($permissions);

        // 🔹 Asignar permisos específicos al rol de Fiscalización
        $fiscalPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'view-clasificaciones',
            'view-mapa-riesgo',
        ])->get();

        $fiscal->syncPermissions($fiscalPermissions);

        // ✅ Mensajes informativos en consola
        $this->command->info('Permisos asignados correctamente a los roles.');
    }
}
