<?php

namespace App\Models;

use App\Models\NivelRiesgo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelRiesgo extends Model
{
    use HasFactory;

    protected $table = 'niveles_riesgo';

    protected $fillable = [
        'nombre',
    ];

    // Relación con subfunciones por riesgo de incendio
    public function subfuncionesIncendio()
    {
        return $this->hasMany(Subfuncion::class, 'riesgo_incendio');
    }

    // Relación con subfunciones por riesgo de colapso
    public function subfuncionesColapso()
    {
        return $this->hasMany(Subfuncion::class, 'riesgo_colapso');
    }
}
