<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $table = 'preguntas';

    protected $fillable = [
        'pregunta',
    ];

    /**
     * Relación con las respuestas del cuestionario.
     * Una pregunta puede tener muchas respuestas.
     */
    public function respuestas()
    {
        return $this->hasMany(Respuestacuestionario::class, 'pregunta_id');
    }
}
