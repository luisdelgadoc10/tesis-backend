<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RespuestaCuestionario;
use App\Models\Clasificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * @OA\Tag(
 *     name="Cuestionario",
 *     description="Gestión de cuestionarios de satisfacción asociados a clasificaciones"
 * )
 */
class CuestionarioController extends Controller
{
    /**
     * Listar todas las respuestas del cuestionario
     *
     * @OA\Get(
     *     path="/api/cuestionarios",
     *     tags={"Cuestionario"},
     *     summary="Listar respuestas de cuestionarios",
     *     description="Obtiene todas las respuestas de los cuestionarios con sus relaciones",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de respuestas del cuestionario"
     *     )
     * )
     */
    public function index()
    {
        $respuestas = RespuestaCuestionario::with([
            'clasificacion.establecimiento',
            'pregunta',
            'nivelSatisfaccion'
        ])->get();

        return response()->json($respuestas);
    }

    /**
     * Registrar respuestas del cuestionario
     *
     * @OA\Post(
     *     path="/api/cuestionarios",
     *     tags={"Cuestionario"},
     *     summary="Registrar respuestas de un cuestionario",
     *     description="Registra las respuestas del cuestionario para una clasificación específica",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"clasificacion_id","respuestas"},
     *             @OA\Property(property="clasificacion_id", type="integer", example=12),
     *             @OA\Property(
     *                 property="respuestas",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="pregunta_id", type="integer", example=3),
     *                     @OA\Property(property="nivel_satisfaccion_id", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cuestionario registrado correctamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="La clasificación ya respondió el cuestionario"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'clasificacion_id' => 'required|exists:clasificaciones,id',
            'respuestas' => 'required|array|min:1',
            'respuestas.*.pregunta_id' => 'required|exists:preguntas,id',
            'respuestas.*.nivel_satisfaccion_id' => 'required|exists:niveles_satisfaccion,id',
        ]);

        $clasificacionId = $request->clasificacion_id;

        if (RespuestaCuestionario::where('clasificacion_id', $clasificacionId)->exists()) {
            return response()->json([
                'message' => 'Esta clasificación ya ha completado el cuestionario.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            $fechaEncuesta = Carbon::now();

            foreach ($request->respuestas as $respuesta) {
                RespuestaCuestionario::create([
                    'clasificacion_id' => $clasificacionId,
                    'pregunta_id' => $respuesta['pregunta_id'],
                    'nivel_satisfaccion_id' => $respuesta['nivel_satisfaccion_id'],
                    'fecha_encuesta' => $fechaEncuesta,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Cuestionario registrado correctamente.',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar el cuestionario.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener las respuestas de una clasificación
     *
     * @OA\Get(
     *     path="/api/cuestionarios/{clasificacionId}",
     *     tags={"Cuestionario"},
     *     summary="Obtener respuestas de un cuestionario por clasificación",
     *     @OA\Parameter(
     *         name="clasificacionId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuestas encontradas"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No hay respuestas registradas"
     *     )
     * )
     */
    public function show($clasificacionId)
    {
        $respuestas = RespuestaCuestionario::with(['pregunta', 'nivelSatisfaccion'])
            ->where('clasificacion_id', $clasificacionId)
            ->get();

        if ($respuestas->isEmpty()) {
            return response()->json(['message' => 'No hay respuestas registradas.'], 404);
        }

        return response()->json($respuestas);
    }
}
