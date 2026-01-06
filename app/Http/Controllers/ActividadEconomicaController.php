<?php

namespace App\Http\Controllers;

use App\Models\ActividadEconomica;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Actividad Económica",
 *     description="Gestión de actividades económicas"
 * )
 */
class ActividadEconomicaController extends Controller
{
    /**
     * Listar todas las actividades (activas e inactivas)
     *
     * @OA\Get(
     *     path="/api/actividades-economicas",
     *     tags={"Actividad Económica"},
     *     summary="Listar todas las actividades económicas",
     *     description="Obtiene todas las actividades económicas, incluyendo las eliminadas (soft delete)",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de actividades",
     *         @OA\JsonContent(type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="descripcion", type="string", example="Comercio minorista"),
     *                 @OA\Property(property="funcion_id", type="integer", example=2),
     *                 @OA\Property(property="estado", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $actividades = ActividadEconomica::with(['funcion:id,nombre'])
            ->withTrashed()
            ->get();

        return response()->json($actividades);
    }

    /**
     * Listar solo actividades activas
     *
     * @OA\Get(
     *     path="/api/actividades-economicas/activas",
     *     tags={"Actividad Económica"},
     *     summary="Listar actividades activas",
     *     description="Obtiene solo las actividades económicas con estado activo",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de actividades activas"
     *     )
     * )
     */
    public function activas()
    {
        $actividades = ActividadEconomica::where('estado', 1)->get();
        return response()->json($actividades);
    }

    /**
     * Crear nueva actividad
     *
     * @OA\Post(
     *     path="/api/actividades-economicas",
     *     tags={"Actividad Económica"},
     *     summary="Crear una nueva actividad económica",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"descripcion","funcion_id"},
     *             @OA\Property(property="descripcion", type="string", example="Servicios profesionales"),
     *             @OA\Property(property="funcion_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Actividad creada correctamente"
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
            'descripcion' => 'required|string',
            'funcion_id' => 'required|exists:funciones,id',
        ]);

        $actividad = ActividadEconomica::create([
            'descripcion' => $request->descripcion,
            'funcion_id' => $request->funcion_id,
        ]);

        return response()->json($actividad, 201);
    }

    /**
     * Mostrar actividad específica
     *
     * @OA\Get(
     *     path="/api/actividades-economicas/{id}",
     *     tags={"Actividad Económica"},
     *     summary="Obtener una actividad económica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actividad encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Actividad no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $actividad = ActividadEconomica::withTrashed()->findOrFail($id);
        return response()->json($actividad);
    }

    /**
     * Actualizar actividad
     *
     * @OA\Put(
     *     path="/api/actividades-economicas/{id}",
     *     tags={"Actividad Económica"},
     *     summary="Actualizar una actividad económica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"descripcion","funcion_id"},
     *             @OA\Property(property="descripcion", type="string", example="Actividad actualizada"),
     *             @OA\Property(property="funcion_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actividad actualizada correctamente"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $actividad = ActividadEconomica::withTrashed()->findOrFail($id);

        $request->validate([
            'descripcion' => 'required|string',
            'funcion_id' => 'required|exists:funciones,id',
        ]);

        $actividad->update([
            'descripcion' => $request->descripcion,
            'funcion_id' => $request->funcion_id,
        ]);

        return response()->json($actividad);
    }

    /**
     * Soft delete (marca estado = 0)
     *
     * @OA\Delete(
     *     path="/api/actividades-economicas/{id}",
     *     tags={"Actividad Económica"},
     *     summary="Eliminar (soft delete) una actividad económica",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actividad eliminada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $actividad = ActividadEconomica::findOrFail($id);
        $actividad->softDeleteWithEstado();
        return response()->json(['message' => 'Actividad eliminada (soft delete)']);
    }

    /**
     * Restaurar actividad eliminada
     *
     * @OA\Post(
     *     path="/api/actividades-economicas/{id}/restore",
     *     tags={"Actividad Económica"},
     *     summary="Restaurar una actividad económica eliminada",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actividad restaurada correctamente"
     *     )
     * )
     */
    public function restore($id)
    {
        $actividad = ActividadEconomica::withTrashed()->findOrFail($id);
        $actividad->restoreWithEstado();
        return response()->json(['message' => 'Actividad restaurada']);
    }
}
