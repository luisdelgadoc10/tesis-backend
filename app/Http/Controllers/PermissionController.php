<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission; // tu modelo extendido con SoftDeletes y estado

class PermissionController extends Controller
{
    // Listar permisos (activos/inactivos/softdeleted)
    public function index(Request $request)
    {
        $query = Permission::query();

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->boolean('withTrashed')) {
            $query->withTrashed();
        }

        return response()->json($query->get());
    }

    // Crear un nuevo permiso
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        $permiso = Permission::create([
            'name' => $request->name,
            'estado' => 1 // por defecto activo
        ]);

        return response()->json($permiso, 201);
    }

    // Mostrar un permiso específico
    public function show($id)
    {
        $permiso = Permission::withTrashed()->findOrFail($id);
        return response()->json($permiso);
    }

    // Actualizar un permiso
    public function update(Request $request, $id)
    {
        $permiso = Permission::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permiso->id,
            'estado' => 'in:0,1'
        ]);

        $permiso->update([
            'name' => $request->name,
            'estado' => $request->estado ?? $permiso->estado
        ]);

        return response()->json($permiso);
    }

    // Desactivar (estado = 0)
    public function deactivate($id)
    {
        $permiso = Permission::findOrFail($id);
        $permiso->update(['estado' => 0]);

        return response()->json(['message' => 'Permiso desactivado']);
    }

    // Activar (estado = 1)
    public function activate($id)
    {
        $permiso = Permission::findOrFail($id);
        $permiso->update(['estado' => 1]);

        return response()->json(['message' => 'Permiso activado']);
    }

    // Soft delete
    public function destroy($id)
    {
        $permiso = Permission::findOrFail($id);
        $permiso->delete();

        return response()->json(['message' => 'Permiso eliminado (soft delete)']);
    }

    // Restaurar un permiso
    public function restore($id)
    {
        $permiso = Permission::withTrashed()->findOrFail($id);
        $permiso->restore();

        return response()->json(['message' => 'Permiso restaurado correctamente']);
    }

}
