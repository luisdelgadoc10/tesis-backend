<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission; // tu modelo extendido con SoftDeletes y estado

class PermissionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/permissions",
     *     summary="Listar permisos",
     *     description="Lista permisos activos, inactivos y/o eliminados lógicamente según filtros",
     *     tags={"Permisos"},
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
     *         description="Incluir registros eliminados lógicamente",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de permisos"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/permissions",
     *     summary="Crear permiso",
     *     tags={"Permisos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="editar-usuarios"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Permiso creado correctamente"
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
            'name' => 'required|unique:permissions,name',
        ]);

        $permiso = Permission::create([
            'name' => $request->name,
            'estado' => 1 // por defecto activo
        ]);

        return response()->json($permiso, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/permissions/{id}",
     *     summary="Mostrar permiso",
     *     description="Devuelve un permiso específico (incluye soft deleted)",
     *     tags={"Permisos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permiso encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Permiso no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $permiso = Permission::withTrashed()->findOrFail($id);
        return response()->json($permiso);
    }

    /**
     * @OA\Put(
     *     path="/api/permissions/{id}",
     *     summary="Actualizar permiso",
     *     tags={"Permisos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="crear-reportes"),
     *             @OA\Property(property="estado", type="integer", enum={0,1}, example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permiso actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/permissions/{id}/deactivate",
     *     summary="Desactivar permiso",
     *     tags={"Permisos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permiso desactivado"
     *     )
     * )
     */
    public function deactivate($id)
    {
        $permiso = Permission::findOrFail($id);
        $permiso->update(['estado' => 0]);

        return response()->json(['message' => 'Permiso desactivado']);
    }

    /**
     * @OA\Patch(
     *     path="/api/permissions/{id}/activate",
     *     summary="Activar permiso",
     *     tags={"Permisos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permiso activado"
     *     )
     * )
     */
    public function activate($id)
    {
        $permiso = Permission::findOrFail($id);
        $permiso->update(['estado' => 1]);

        return response()->json(['message' => 'Permiso activado']);
    }

    /**
     * @OA\Delete(
     *     path="/api/permissions/{id}",
     *     summary="Eliminar permiso",
     *     description="Elimina un permiso mediante soft delete",
     *     tags={"Permisos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permiso eliminado (soft delete)"
     *     )
     * )
     */
    public function destroy($id)
    {
        $permiso = Permission::findOrFail($id);
        $permiso->delete();

        return response()->json(['message' => 'Permiso eliminado (soft delete)']);
    }

    /**
     * @OA\Patch(
     *     path="/api/permissions/{id}/restore",
     *     summary="Restaurar permiso",
     *     tags={"Permisos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Permiso restaurado correctamente"
     *     )
     * )
     */
    public function restore($id)
    {
        $permiso = Permission::withTrashed()->findOrFail($id);
        $permiso->restore();

        return response()->json(['message' => 'Permiso restaurado correctamente']);
    }
}
