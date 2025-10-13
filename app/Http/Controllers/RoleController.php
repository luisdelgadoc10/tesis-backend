<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role; // usamos tu modelo extendido
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // Listar roles (activos/inactivos/softdeleted)
    public function index(Request $request)
    {
        $query = Role::with('permissions');

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->boolean('withTrashed')) {
            $query->withTrashed();
        }

        return response()->json($query->get());
    }

    // Crear un nuevo rol
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'estado' => 1 // por defecto activo
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json($role, 201);
    }

    // Mostrar un rol con sus permisos
    public function show($id)
    {
        $role = Role::withTrashed()->with('permissions')->findOrFail($id);
        return response()->json($role);
    }

    // Actualizar un rol
    public function update(Request $request, $id)
    {
        $role = Role::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'estado' => 'in:0,1'
        ]);

        $role->update([
            'name' => $request->name,
            'estado' => $request->estado ?? $role->estado
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json($role);
    }

    // Desactivar (estado = 0)
    public function deactivate($id)
    {
        $role = Role::findOrFail($id);
        $role->update(['estado' => 0]);

        return response()->json(['message' => 'Rol desactivado']);
    }

    // Activar (estado = 1)
    public function activate($id)
    {
        $role = Role::findOrFail($id);
        $role->update(['estado' => 1]);

        return response()->json(['message' => 'Rol activado']);
    }

    // Soft delete
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Rol eliminado (soft delete)']);
    }

    // Restaurar un rol
    public function restore($id)
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->restore();

        return response()->json(['message' => 'Rol restaurado correctamente']);
    }

    
}
