<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subfuncion extends Model
{
    use HasFactory;

    protected $table = 'subfunciones';

    protected $fillable = [
        'funcion_id',
        'codigo',
        'descripcion',
        'riesgo_incendio',
        'riesgo_colapso',
    ];

    // Relación con funcion
    public function funcion()
    {
        return $this->belongsTo(Funcion::class);
    }

    // Relación con niveles de riesgo
    public function riesgoIncendio()
    {
        return $this->belongsTo(NivelRiesgo::class, 'riesgo_incendio');
    }

    public function riesgoColapso()
    {
        return $this->belongsTo(NivelRiesgo::class, 'riesgo_colapso');
    }
}
