<?php

namespace App\Http\Controllers;

use App\Models\Subfuncion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="Subfunciones",
 *     description="Gestión de subfunciones asociadas a funciones y niveles de riesgo"
 * )
 */
class SubfuncionController extends Controller
{
    /**
     * Listar todas las subfunciones con sus relaciones.
     *
     * @OA\Get(
     *     path="/api/subfunciones",
     *     tags={"Subfunciones"},
     *     summary="Listar subfunciones",
     *     description="Obtiene el listado de todas las subfunciones con su función y niveles de riesgo",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de subfunciones",
     *         @OA\JsonContent(type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="codigo", type="string", example="SF-001"),
     *                 @OA\Property(property="descripcion", type="string", example="Subfunción administrativa"),
     *                 @OA\Property(property="funcion", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Función principal")
     *                 ),
     *                 @OA\Property(property="riesgoIncendio", type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="nombre", type="string", example="Alto")
     *                 ),
     *                 @OA\Property(property="riesgoColapso", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nombre", type="string", example="Bajo")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $subfunciones = Subfuncion::with(['funcion:id,nombre', 'riesgoIncendio:id,nombre', 'riesgoColapso:id,nombre'])->get();

        return response()->json($subfunciones);
    }

    /**
     * Guardar una nueva subfunción.
     *
     * @OA\Post(
     *     path="/api/subfunciones",
     *     tags={"Subfunciones"},
     *     summary="Crear subfunción",
     *     description="Registra una nueva subfunción",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"funcion_id","codigo","descripcion","riesgo_incendio","riesgo_colapso"},
     *             @OA\Property(property="funcion_id", type="integer", example=1),
     *             @OA\Property(property="codigo", type="string", example="SF-002"),
     *             @OA\Property(property="descripcion", type="string", example="Subfunción operativa"),
     *             @OA\Property(property="riesgo_incendio", type="integer", example=2),
     *             @OA\Property(property="riesgo_colapso", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Subfunción creada correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'funcion_id'      => 'required|exists:funciones,id',
            'codigo'          => 'required|string|max:20|unique:subfunciones,codigo',
            'descripcion'     => 'required|string|max:255',
            'riesgo_incendio' => 'required|exists:niveles_riesgo,id',
            'riesgo_colapso'  => 'required|exists:niveles_riesgo,id',
        ]);

        $subfuncion = Subfuncion::create($validated);

        return response()->json([
            'message' => 'Subfunción creada correctamente',
            'data'    => $subfuncion->load(['funcion', 'riesgoIncendio', 'riesgoColapso']),
        ], 201);
    }

    /**
     * Mostrar una subfunción específica.
     *
     * @OA\Get(
     *     path="/api/subfunciones/{id}",
     *     tags={"Subfunciones"},
     *     summary="Obtener subfunción",
     *     description="Obtiene el detalle de una subfunción por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle de la subfunción"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subfunción no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $subfuncion = Subfuncion::with(['funcion', 'riesgoIncendio', 'riesgoColapso'])->findOrFail($id);
        return response()->json($subfuncion);
    }

    /**
     * Actualizar una subfunción.
     *
     * @OA\Put(
     *     path="/api/subfunciones/{id}",
     *     tags={"Subfunciones"},
     *     summary="Actualizar subfunción",
     *     description="Actualiza la información de una subfunción existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"funcion_id","codigo","descripcion","riesgo_incendio","riesgo_colapso"},
     *             @OA\Property(property="funcion_id", type="integer", example=1),
     *             @OA\Property(property="codigo", type="string", example="SF-002"),
     *             @OA\Property(property="descripcion", type="string", example="Subfunción actualizada"),
     *             @OA\Property(property="riesgo_incendio", type="integer", example=1),
     *             @OA\Property(property="riesgo_colapso", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subfunción actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Subfunción no encontrada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $subfuncion = Subfuncion::findOrFail($id);

        $validated = $request->validate([
            'funcion_id'      => 'required|exists:funciones,id',
            'codigo'          => [
                'required', 'string', 'max:20',
                Rule::unique('subfunciones')->ignore($subfuncion->id),
            ],
            'descripcion'     => 'required|string|max:255',
            'riesgo_incendio' => 'required|exists:niveles_riesgo,id',
            'riesgo_colapso'  => 'required|exists:niveles_riesgo,id',
        ]);

        $subfuncion->update($validated);

        return response()->json([
            'message' => 'Subfunción actualizada correctamente',
            'data'    => $subfuncion->load(['funcion', 'riesgoIncendio', 'riesgoColapso']),
        ]);
    }
}
