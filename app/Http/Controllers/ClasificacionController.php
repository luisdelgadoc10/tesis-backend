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

class ClasificacionController extends Controller
{
    /**
     * Listar clasificaciones con filtros opcionales
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
     */
    public function store(Request $request)
    {
        $request->validate([
            'establecimiento_id'      => 'required|exists:establecimientos,id',
            'actividad_economica_id'  => 'required|exists:actividad_economica,id',
        ]);

        // Verificar relación entre establecimiento y actividad económica
        $establecimiento = Establecimiento::findOrFail($request->establecimiento_id);
        if ($establecimiento->actividad_economica_id !== (int) $request->actividad_economica_id) {
            throw ValidationException::withMessages([
                'actividad_economica_id' => 'El establecimiento no está asociado a la actividad económica seleccionada.'
            ]);
        }

        $actividad = ActividadEconomica::findOrFail($request->actividad_economica_id);


        // Obtener función asociada
        $funcion = $actividad->funcion;
        if (!$funcion) {
            return response()->json(['error' => 'La actividad económica no tiene una función asociada.'], 422);
        }

        // ✅ Preparamos los datos para ML según la función
        $datosEntrada = $this->prepararDatosML($funcion->nombre, $request);

        // ✅ Llamada al modelo de ML
        $resultadoML = $this->llamarModeloML($funcion->nombre, $datosEntrada);

        // ✅ Guardamos en la BD
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
     */
    public function show($id)
    {
        $clasificacion = Clasificacion::with(['establecimiento', 'actividadEconomica', 'funcion', 'detalle'])
            ->findOrFail($id);

        return response()->json($clasificacion);
    }

    /**
     * Eliminar (baja lógica) una clasificación
     */
    public function destroy($id)
    {
        $clasificacion = Clasificacion::findOrFail($id);
        $clasificacion->update(['estado' => false]);

        return response()->json(null, 204);
    }

    /**
     * Restaurar una clasificación eliminada
     */
    public function restore($id)
    {
        $clasificacion = Clasificacion::withTrashed()->findOrFail($id);
        $clasificacion->restore();

        return response()->json($clasificacion->fresh());
    }

    // =====================================================
    // MÉTODOS AUXILIARES
    // =====================================================

    /**
    * Armar payload para ML según la función
    */
    private function prepararDatosML(string $funcionNombre, Request $request): array
    {
        switch ($funcionNombre) {
            case 'SALUD':
                return [
                    'nivel_atencion'              => $request->input('nivel_atencion'),
                    'tipo_establecimiento'        => $request->input('tipo_establecimiento'),
                    'camas_internamiento' => (string) $request->input('camas_internamiento'),
                    //'camas_internamiento'         => $request->input('camas_internamiento'),
                    'usuarios_no_autosuficientes' => $request->boolean('usuarios_no_autosuficientes'),
                    'capacidad_atencion'  => (string) $request->input('capacidad_atencion'),
                    //'capacidad_atencion'          => $request->input('capacidad_atencion'),
                    'servicios_disponibles'       => $request->input('servicios_disponibles', []),
                    'urgencias_24h'               => $request->boolean('urgencias_24h'),
                    'num_especialidades'  => (string) $request->input('num_especialidades'),
                    //'num_especialidades'          => $request->input('num_especialidades'),
                    'num_pisos'           => (string) $request->input('num_pisos'),
                    //'num_pisos'                   => $request->input('num_pisos'),
                    'area_construida'             => (float) $request->input('area_construida', 0),
                    'personal_medico_total'       => (int) $request->input('personal_medico_total', 0),
                ];

            case 'ENCUENTRO':
                return [
                    'tipo_actividad'        => $request->input('tipo_actividad'),
                    'carga_ocupantes'       => (int) $request->input('carga_ocupantes', 0),
                    'ubicado_en_sotano'     => $request->boolean('ubicado_en_sotano'),
                    'num_pisos'             => (int) $request->input('num_pisos', 0),
                    'area_total_m2'         => (float) $request->input('area_total_m2', 0),
                    'evento_recurrente'     => $request->boolean('evento_recurrente'),
                    'horario_funcionamiento'=> $request->input('horario_funcionamiento'),
                ];

            case 'HOSPEDAJE':
                return [
                    'categoria_estrellas'     => (int) $request->input('categoria_estrellas', 0),
                    'tipo_hospedaje'          => $request->input('tipo_hospedaje'),
                    'num_pisos'               => (int) $request->input('num_pisos', 0),
                    'tiene_sotano'            => $request->boolean('tiene_sotano'),
                    'num_habitaciones'        => (int) $request->input('num_habitaciones', 0),
                    'capacidad_ocupantes'     => (int) $request->input('capacidad_ocupantes', 0),
                    'uso_mixto'               => $request->boolean('uso_mixto'),
                    'tiene_estacionamiento'   => $request->boolean('tiene_estacionamiento'),
                    'estacionamiento_en_sotano'=> $request->boolean('estacionamiento_en_sotano'),
                ];

            case 'EDUCACION':
                return [
                    'nivel_educativo'              => $request->input('nivel_educativo'),
                    'tipo_institucion'             => $request->input('tipo_institucion'),
                    'numero_pisos'                 => $request->input('numero_pisos'),
                    'area_construida_m2'           => $request->input('area_construida_m2'),
                    'atiende_personas_discapacidad'=> $request->boolean('atiende_personas_discapacidad'),
                    'capacidad_alumnos'            => $request->input('capacidad_alumnos'),
                    'cantidad_aulas'               => (int) $request->input('cantidad_aulas', 0),
                    'tipo_edificacion'             => $request->input('tipo_edificacion'),
                ];

            case 'INDUSTRIAL':
                return [
                    'tipo_proceso_productivo'           => $request->input('tipo_proceso_productivo'),
                    'tipo_maquinaria_principal'         => $request->input('tipo_maquinaria_principal'),
                    'escala_produccion'                 => $request->input('escala_produccion'),
                    'trabaja_materiales_explosivos'     => $request->boolean('trabaja_materiales_explosivos'),
                    'tipo_producto_fabricado'           => $request->input('tipo_producto_fabricado'),
                    'nivel_peligrosidad_insumos'        => $request->input('nivel_peligrosidad_insumos'),
                    'area_produccion_m2'                => $request->input('area_produccion_m2'),
                    'numero_trabajadores'               => $request->input('numero_trabajadores'),
                    'tiene_area_comercializacion_integrada'=> $request->boolean('tiene_area_comercializacion_integrada'),
                    'tipo_establecimiento'              => $request->input('tipo_establecimiento'),
                ];

            case 'OFICINAS ADMINISTRATIVAS':
                return [
                    'numero_pisos_edificacion'          => $request->input('numero_pisos_edificacion'),
                    'area_techada_por_piso_m2'          => $request->input('area_techada_por_piso_m2'),
                    'area_techada_total_m2'             => $request->input('area_techada_total_m2'),
                    'año_conformidad_obra'              => (int) $request->input('año_conformidad_obra', 0),
                    'antigüedad_conformidad_años'       => $request->input('antigüedad_conformidad_años'),
                    'tiene_conformidad_obra_vigente'    => $request->boolean('tiene_conformidad_obra_vigente'),
                    'tipo_conformidad'                  => $request->input('tipo_conformidad'),
                    'tipo_ocupacion_edificio'           => $request->input('tipo_ocupacion_edificio'),
                    'areas_comunes_tienen_itse_vigente' => $request->input('areas_comunes_tienen_itse_vigente'),
                    'piso_ubicacion_establecimiento'    => $request->input('piso_ubicacion_establecimiento'),
                    'uso_diseño_original'               => $request->input('uso_diseño_original'),
                    'ha_tenido_remodelaciones_ampliaciones'=> $request->boolean('ha_tenido_remodelaciones_ampliaciones'),
                ];

            case 'COMERCIO':
                return [
                    'numero_pisos_edificacion'              => $request->input('numero_pisos_edificacion'),
                    'area_techada_total_m2'                 => $request->input('area_techada_total_m2'),
                    'area_venta_m2'                         => $request->input('area_venta_m2'),
                    'tipo_establecimiento_comercial'        => $request->input('tipo_establecimiento_comercial'),
                    'modalidad_operacion'                   => $request->input('modalidad_operacion'),
                    'uso_edificacion'                       => $request->input('uso_edificacion'),
                    'tipo_licencia_funcionamiento'          => $request->input('tipo_licencia_funcionamiento'),
                    'edificio_tiene_licencia_corporativa'   => $request->input('edificio_tiene_licencia_corporativa'),
                    'comercializa_productos_explosivos_pirotecnicos'=> $request->boolean('comercializa_productos_explosivos_pirotecnicos'),
                    'tipo_productos_peligrosos'             => $request->input('tipo_productos_peligrosos'),
                    'formato_comercial'                     => $request->input('formato_comercial'),
                    'numero_locales_comerciales_edificio'   => $request->input('numero_locales_comerciales_edificio'),
                ];

            case 'ALMACEN':
                return [
                    'tipo_cobertura'                       => $request->input('tipo_cobertura'),
                    'porcentaje_area_techada'              => $request->input('porcentaje_area_techada'),
                    'tipo_cerramiento'                     => $request->input('tipo_cerramiento'),
                    'tipo_establecimiento'                 => $request->input('tipo_establecimiento'),
                    'uso_principal'                        => $request->input('uso_principal'),
                    'almacena_productos_explosivos_pirotecnicos'=> $request->boolean('almacena_productos_explosivos_pirotecnicos'),
                    'tipo_productos_almacenados'           => $request->input('tipo_productos_almacenados'),
                    'nivel_peligrosidad_nfpa'              => $request->input('nivel_peligrosidad_nfpa'),
                    'tiene_areas_administrativas_techadas' => $request->boolean('tiene_areas_administrativas_techadas'),
                    'area_administrativa_servicios_m2'     => $request->input('area_administrativa_servicios_m2'),
                ];

            default:
                return $request->all();
        }
    }


    /**
     * Llamar al servicio de ML según la función
     */
    private function llamarModeloML(string $funcionNombre, array $datosEntrada)
    {
        $endpoints = [
            'SALUD'      => '/funcion-salud',
            'ENCUENTRO'  => '/funcion-encuentro',
            'HOSPEDAJE'  => '/funcion-hospedaje',
            'EDUCACION'  => '/funcion-educacion',
            'INDUSTRIAL' => '/funcion-industrial',
            'OFICINAS ADMINISTRATIVAS'   => '/funcion-oficinas',
            'COMERCIO'   => '/funcion-comercio',
            'ALMACEN'    => '/funcion-almacen',
        ];

        $endpoint = $endpoints[$funcionNombre] ?? null;

        if (!$endpoint) {
            return ['error' => 'Función no soportada en el modelo de ML.'];
        }

        try {
            // ✅ Usar variable de entorno
            $baseUrl = rtrim(env('ML_API_URL'), '/');
            $response = Http::post($baseUrl . $endpoint, $datosEntrada);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'error'  => 'Error en el servicio de ML',
                'status' => $response->status(),
                'body'   => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'error'   => 'No se pudo conectar al servicio de ML',
                'detalle' => $e->getMessage(),
            ];
        }
    }
}
