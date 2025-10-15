<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Clasificacion;
use App\Models\Pregunta;
use App\Models\NivelSatisfaccion;
use App\Models\RespuestaCuestionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicCuestionarioController extends Controller
{
    /**
     * Mostrar las preguntas y opciones de una encuesta pública
     */
    public function show($token)
    {
        $clasificacion = Clasificacion::where('token_encuesta', $token)->first();

        if (!$clasificacion) {
            return response()->json(['message' => 'Enlace de encuesta inválido'], 404);
        }

        // Verificar si ya completó el cuestionario
        $yaRespondido = RespuestaCuestionario::where('clasificacion_id', $clasificacion->id)->exists();
        if ($yaRespondido) {
            return response()->json(['message' => 'Esta encuesta ya fue completada.'], 403);
        }

        return response()->json([
            'clasificacion' => $clasificacion->id,
            'preguntas' => Pregunta::select('id', 'pregunta')->get(),
            'niveles_satisfaccion' => NivelSatisfaccion::select('id', 'nombre', 'valor')->get(),
        ]);
    }

    /**
     * Registrar respuestas de una encuesta pública
     */
    public function store(Request $request, $token)
    {
        $clasificacion = Clasificacion::where('token_encuesta', $token)->first();

        if (!$clasificacion) {
            return response()->json(['message' => 'Token inválido'], 404);
        }

        if (RespuestaCuestionario::where('clasificacion_id', $clasificacion->id)->exists()) {
            return response()->json(['message' => 'Esta encuesta ya fue respondida.'], 403);
        }

        $request->validate([
            'respuestas' => 'required|array|min:1',
            'respuestas.*.pregunta_id' => 'required|exists:preguntas,id',
            'respuestas.*.nivel_satisfaccion_id' => 'required|exists:niveles_satisfaccion,id',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->respuestas as $r) {
                RespuestaCuestionario::create([
                    'clasificacion_id' => $clasificacion->id,
                    'pregunta_id' => $r['pregunta_id'],
                    'nivel_satisfaccion_id' => $r['nivel_satisfaccion_id'],
                    'fecha_cuestionario' => Carbon::now(),
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Encuesta enviada correctamente.'], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al guardar la encuesta.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
