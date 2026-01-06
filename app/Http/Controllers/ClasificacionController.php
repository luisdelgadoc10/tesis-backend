<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Clasificacion;
use App\Models\ClasificacionDetalle;
use App\Models\ActividadEconomica;
use App\Models\Establecimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Clasificación",
 *     description="Gestión de clasificaciones de riesgo mediante ML"
 * )
 */
class ClasificacionController extends Controller
{
    /**
     * Listar clasificaciones con filtros opcionales
     *
     * @OA\Get(
     *     path="/api/clasificaciones",
     *     tags={"Clasificación"},
     *     summary="Listar clasificaciones",
     *     description="Obtiene el listado de clasificaciones con filtros opcionales por establecimiento y función",
     *     @OA\Parameter(
     *         name="establecimiento_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="funcion_id",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de clasificaciones"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Clasificacion::with(['establecimiento', 'actividadEconomica', 'funcion', 'detalle']);

        if ($request->filled('establecimiento_id')) {
            $query->where('establecimiento_id', $request->establecimiento_id);
        }

        if ($request->filled('funcion_id')) {
            $query->where('funcion_id', $request->funcion_id);
        }

        return response()->json($query->get());
    }

    /**
     * Crear una nueva clasificación
     *
     * @OA\Post(
     *     path="/api/clasificaciones",
     *     tags={"Clasificación"},
     *     summary="Crear una nueva clasificación",
     *     description="Crea una clasificación y ejecuta el modelo de Machine Learning según la función asociada",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"establecimiento_id","actividad_economica_id"},
     *             @OA\Property(property="establecimiento_id", type="integer", example=10),
     *             @OA\Property(property="actividad_economica_id", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Clasificación creada correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación o reglas de negocio"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'establecimiento_id'      => 'required|exists:establecimientos,id',
            'actividad_economica_id'  => 'required|exists:actividad_economica,id',
        ]);

        $establecimiento = Establecimiento::findOrFail($request->establecimiento_id);
        if ($establecimiento->actividad_economica_id !== (int) $request->actividad_economica_id) {
            throw ValidationException::withMessages([
                'actividad_economica_id' => 'El establecimiento no está asociado a la actividad económica seleccionada.'
            ]);
        }

        $actividad = ActividadEconomica::findOrFail($request->actividad_economica_id);

        $funcion = $actividad->funcion;
        if (!$funcion) {
            return response()->json(['error' => 'La actividad económica no tiene una función asociada.'], 422);
        }

        $datosEntrada = $this->prepararDatosML($funcion->nombre, $request);
        $resultadoML = $this->llamarModeloML($funcion->nombre, $datosEntrada);

        $clasificacion = DB::transaction(function () use ($request, $actividad, $funcion, $datosEntrada, $resultadoML) {
            $clasificacion = Clasificacion::create([
                'establecimiento_id'     => $request->establecimiento_id,
                'actividad_economica_id' => $actividad->id,
                'funcion_id'             => $funcion->id,
                'user_id'                => auth()->id(),
                'fecha_clasificacion'    => now(),
                'estado'                 => true,
            ]);

            ClasificacionDetalle::create([
                'clasificacion_id' => $clasificacion->id,
                'datos_entrada'    => $datosEntrada,
                'resultado_modelo' => $resultadoML,
            ]);

            return $clasificacion->load(['establecimiento', 'actividadEconomica', 'funcion', 'detalle']);
        });

        return response()->json($clasificacion, 201);
    }

    /**
     * Mostrar una clasificación por ID
     *
     * @OA\Get(
     *     path="/api/clasificaciones/{id}",
     *     tags={"Clasificación"},
     *     summary="Obtener una clasificación",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Clasificación encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Clasificación no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $clasificacion = Clasificacion::with(['establecimiento', 'actividadEconomica', 'funcion', 'detalle'])
            ->findOrFail($id);

        return response()->json($clasificacion);
    }

    /**
     * Eliminar (baja lógica) una clasificación
     *
     * @OA\Delete(
     *     path="/api/clasificaciones/{id}",
     *     tags={"Clasificación"},
     *     summary="Eliminar una clasificación (baja lógica)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Clasificación eliminada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $clasificacion = Clasificacion::findOrFail($id);
        $clasificacion->update(['estado' => false]);

        return response()->json(null, 204);
    }

    /**
     * Restaurar una clasificación eliminada
     *
     * @OA\Post(
     *     path="/api/clasificaciones/{id}/restore",
     *     tags={"Clasificación"},
     *     summary="Restaurar una clasificación eliminada",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Clasificación restaurada correctamente"
     *     )
     * )
     */
    public function restore($id)
    {
        $clasificacion = Clasificacion::withTrashed()->findOrFail($id);
        $clasificacion->restore();

        return response()->json($clasificacion->fresh());
    }
}
