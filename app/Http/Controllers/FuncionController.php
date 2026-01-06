<?php

namespace App\Http\Controllers;

use App\Models\Funcion;
use Illuminate\Http\Request;

class FuncionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/funciones",
     *     summary="Listar todas las funciones",
     *     description="Devuelve todas las funciones incluyendo las eliminadas lógicamente",
     *     tags={"Funciones"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de funciones"
     *     )
     * )
     */
    public function index()
    {
        $funciones = Funcion::withTrashed()->get();
        return response()->json($funciones);
    }

    /**
     * @OA\Get(
     *     path="/api/funciones/activas",
     *     summary="Listar funciones activas",
     *     description="Devuelve únicamente las funciones con estado activo",
     *     tags={"Funciones"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de funciones activas"
     *     )
     * )
     */
    public function activas()
    {
        $funciones = Funcion::where('estado', 1)->get();
        return response()->json($funciones);
    }

    /**
     * @OA\Post(
     *     path="/api/funciones",
     *     summary="Crear nueva función",
     *     tags={"Funciones"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Inspección")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Función creada correctamente"
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
            'nombre' => 'required|string|unique:funciones,nombre',
        ]);

        $funcion = Funcion::create([
            'nombre' => $request->nombre,
        ]);

        return response()->json($funcion, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/funciones/{id}",
     *     summary="Mostrar una función",
     *     tags={"Funciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Función encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Función no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $funcion = Funcion::withTrashed()->findOrFail($id);
        return response()->json($funcion);
    }

    /**
     * @OA\Put(
     *     path="/api/funciones/{id}",
     *     summary="Actualizar función",
     *     tags={"Funciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(property="nombre", type="string", example="Fiscalización")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Función actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $funcion = Funcion::withTrashed()->findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|unique:funciones,nombre,' . $funcion->id,
        ]);

        $funcion->update([
            'nombre' => $request->nombre,
        ]);

        return response()->json($funcion);
    }

    /**
     * @OA\Delete(
     *     path="/api/funciones/{id}",
     *     summary="Eliminar función",
     *     description="Elimina la función de forma lógica (soft delete)",
     *     tags={"Funciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Función eliminada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $funcion = Funcion::findOrFail($id);
        $funcion->delete(); // ya maneja estado automáticamente
        return response()->json(['message' => 'Función eliminada (soft delete)']);
    }

    /**
     * @OA\Patch(
     *     path="/api/funciones/{id}/restore",
     *     summary="Restaurar función eliminada",
     *     tags={"Funciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Función restaurada correctamente"
     *     )
     * )
     */
    public function restore($id)
    {
        $funcion = Funcion::withTrashed()->findOrFail($id);
        $funcion->restore(); // estado = 1 automáticamente
        return response()->json(['message' => 'Función restaurada']);
    }
}
