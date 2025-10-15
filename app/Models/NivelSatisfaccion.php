<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelSatisfaccion extends Model
{
    use HasFactory;

    protected $table = 'niveles_satisfaccion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'valor', // opcional si manejas un número o nivel (1, 2, 3, 4)
    ];

    /**
     * Relación con las respuestas del cuestionario.
     * Un nivel puede tener muchas respuestas.
     */
    public function respuestas()
    {
        return $this->hasMany(Respuestacuestionario::class, 'nivel_satisfaccion_id');
    }
}
