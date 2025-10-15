<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RespuestaCuestionario;
use App\Models\Clasificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CuestionarioController extends Controller
{
    /**
     * Registrar respuestas del cuestionario
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

        // ✅ Verificar si esta clasificación ya respondió el cuestionario
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
     * Obtener las respuestas de una clasificación (opcional)
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
