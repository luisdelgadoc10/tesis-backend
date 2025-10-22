<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestaCuestionario extends Model
{
    use HasFactory;

    protected $table = 'respuestas_cuestionario';

    protected $fillable = [
        'clasificacion_id',
        'pregunta_id',
        'nivel_satisfaccion_id',
        'fecha_encuesta',
    ];

    protected $casts = [
        'fecha_encuesta' => 'datetime',
    ];

    /**
     * 🔹 Una respuesta pertenece a una clasificación
     */
    public function clasificacion()
    {
        return $this->belongsTo(Clasificacion::class);
    }

    /**
     * 🔹 Una respuesta pertenece a una pregunta
     */
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }

    /**
     * 🔹 Una respuesta tiene un nivel de satisfacción
     */
    public function nivelSatisfaccion()
    {
        return $this->belongsTo(NivelSatisfaccion::class);
    }
}
