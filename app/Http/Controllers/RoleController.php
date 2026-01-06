<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/roles",
     *     summary="Listar roles",
     *     description="Lista roles activos, inactivos y/o eliminados lógicamente según filtros",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="estado",
     *         in="query",
     *         required=false,
     *         description="Filtrar por estado (1=activo, 0=inactivo)",
     *         @OA\Schema(type="integer", enum={0,1})
     *     ),
     *     @OA\Parameter(
     *         name="withTrashed",
     *         in="query",
     *         required=false,
     *         description="Incluir roles eliminados lógicamente",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de roles"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     summary="Crear un nuevo rol",
     *     description="Crea un rol y opcionalmente asigna permisos",
     *     tags={"Roles"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Administrador"),
     *             @OA\Property(
     *                 property="permissions",
     *                 type="array",
     *                 @OA\Items(type="integer"),
     *                 example={1,2,3}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rol creado correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'estado' => 1
        ]);

        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)
                ->where('guard_name', 'web')
                ->get();
            $role->syncPermissions($permissions);
        }

        return response()->json($role->load('permissions'), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     summary="Mostrar un rol",
     *     description="Obtiene un rol específico junto con sus permisos",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $role = Role::withTrashed()->with('permissions')->findOrFail($id);
        return response()->json($role);
    }

    /**
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     summary="Actualizar un rol",
     *     description="Actualiza el nombre, estado y permisos de un rol",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Supervisor"),
     *             @OA\Property(
     *                 property="permissions",
     *                 type="array",
     *                 @OA\Items(type="integer"),
     *                 example={2,3}
     *             ),
     *             @OA\Property(property="estado", type="integer", enum={0,1})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
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
            $permissions = Permission::whereIn('id', $request->permissions)
                ->where('guard_name', 'web')
                ->get();
            $role->syncPermissions($permissions);
        }

        return response()->json($role->load('permissions'));
    }

    /**
     * @OA\Patch(
     *     path="/api/roles/{id}/deactivate",
     *     summary="Desactivar rol",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Rol desactivado")
     * )
     */
    public function deactivate($id)
    {
        $role = Role::findOrFail($id);
        $role->update(['estado' => 0]);

        return response()->json(['message' => 'Rol desactivado']);
    }

    /**
     * @OA\Patch(
     *     path="/api/roles/{id}/activate",
     *     summary="Activar rol",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Rol activado")
     * )
     */
    public function activate($id)
    {
        $role = Role::findOrFail($id);
        $role->update(['estado' => 1]);

        return response()->json(['message' => 'Rol activado']);
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     summary="Eliminar rol (soft delete)",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Rol eliminado")
     * )
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Rol eliminado (soft delete)']);
    }

    /**
     * @OA\Patch(
     *     path="/api/roles/{id}/restore",
     *     summary="Restaurar rol eliminado",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Rol restaurado correctamente")
     * )
     */
    public function restore($id)
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->restore();

        return response()->json(['message' => 'Rol restaurado correctamente']);
    }
}
