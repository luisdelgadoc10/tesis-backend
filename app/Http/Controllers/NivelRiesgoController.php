<?php

namespace App\Http\Controllers;

use App\Models\NivelRiesgo;
use Illuminate\Http\Request;

class NivelRiesgoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/niveles-riesgo",
     *     summary="Listar niveles de riesgo",
     *     description="Devuelve todos los niveles de riesgo registrados",
     *     tags={"Nivel de Riesgo"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de niveles de riesgo"
     *     )
     * )
     */
    public function index()
    {
        $niveles = NivelRiesgo::all();
        return response()->json($niveles);
    }

    /**
     * @OA\Get(
     *     path="/api/niveles-riesgo/{id}",
     *     summary="Mostrar nivel de riesgo",
     *     description="Devuelve un nivel de riesgo específico por ID",
     *     tags={"Nivel de Riesgo"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Nivel de riesgo encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nivel de riesgo no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $nivel = NivelRiesgo::find($id);

        if (!$nivel) {
            return response()->json(['message' => 'Nivel de riesgo no encontrado'], 404);
        }

        return response()->json($nivel);
    }

    /**
     * @OA\Post(
     *     path="/api/niveles-riesgo",
     *     summary="Crear nivel de riesgo",
     *     tags={"Nivel de Riesgo"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre"},
     *             @OA\Property(
     *                 property="nombre",
     *                 type="string",
     *                 maxLength=255,
     *                 example="Alto"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Nivel de riesgo creado correctamente"
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
            'nombre' => 'required|string|max:255',
        ]);

        $nivel = NivelRiesgo::create([
            'nombre' => $request->nombre,
        ]);

        return response()->json([
            'message' => 'Nivel de riesgo creado correctamente',
            'data' => $nivel,
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/niveles-riesgo/{id}",
     *     summary="Actualizar nivel de riesgo",
     *     tags={"Nivel de Riesgo"},
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
     *             @OA\Property(
     *                 property="nombre",
     *                 type="string",
     *                 maxLength=255,
     *                 example="Riesgo Medio"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Nivel de riesgo actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nivel de riesgo no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $nivel = NivelRiesgo::find($id);

        if (!$nivel) {
            return response()->json(['message' => 'Nivel de riesgo no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $nivel->update([
            'nombre' => $request->nombre,
        ]);

        return response()->json([
            'message' => 'Nivel de riesgo actualizado correctamente',
            'data' => $nivel,
        ]);
    }
}
