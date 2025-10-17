<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subfuncion;
use App\Models\NivelRiesgo;

class ClasificacionDetalle extends Model
{
    protected $fillable = [
        'clasificacion_id',
        'datos_entrada',
        'resultado_modelo',
        'riesgo_incendio',
        'riesgo_colapso',
        'riesgo_final',
        'tiempo_envio_reporte',
    ];

    protected $casts = [
        'datos_entrada' => 'array',
        'resultado_modelo' => 'array',
    ];

    public function clasificacion()
    {
        return $this->belongsTo(Clasificacion::class);
    }

    // Ya no necesitamos las relaciones belongsTo hacia NivelRiesgo
    // porque ahora guardaremos directamente el texto.
    // Pero si quieres mantenerlas para consultas futuras, puedes dejarlas opcionales.

    protected static function booted()
    {
        static::saving(function ($detalle) {
            $detalle->calcularRiesgos();
        });
    }

    public function calcularRiesgos()
    {
        $resultado = $this->resultado_modelo ?? [];

        $subfuncionNombre = null;
        foreach ($resultado as $key => $value) {
            if (str_starts_with($key, 'subfuncion_')) {
                $subfuncionNombre = $value;
                break;
            }
        }

        if ($subfuncionNombre) {
            $subfuncion = Subfuncion::whereRaw('LOWER(codigo) = ?', [strtolower($subfuncionNombre)])->first();

            if ($subfuncion) {
                // Buscamos los nombres de los niveles de riesgo
                $riesgoIncendio = NivelRiesgo::find($subfuncion->riesgo_incendio);
                $riesgoColapso = NivelRiesgo::find($subfuncion->riesgo_colapso);

                // Guardamos directamente el nombre
                $this->riesgo_incendio = $riesgoIncendio?->nombre ?? 'N/A';
                $this->riesgo_colapso = $riesgoColapso?->nombre ?? 'N/A';

                // Calculamos el riesgo final tomando el más alto según prioridad
                $niveles = ['Bajo' => 1, 'Medio' => 2, 'Alto' => 3, 'Muy Alto' => 4];
                $nivelFinal = $niveles[$this->riesgo_incendio] >= $niveles[$this->riesgo_colapso]
                    ? $this->riesgo_incendio
                    : $this->riesgo_colapso;

                $this->riesgo_final = $nivelFinal;
            }
        }
    }
}
