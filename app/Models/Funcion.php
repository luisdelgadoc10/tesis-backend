<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Funcion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'funciones';

    protected $dates = ['deleted_at'];

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'nombre',
        'estado',
    ];

    /**
     * Valores por defecto
     */
    protected $attributes = [
        'estado' => 1,
    ];

    /**
     * Manejo automático de estado con SoftDeletes
     */
    protected static function booted()
    {
        // Cuando se hace soft delete
        static::deleting(function ($funcion) {
            if (! $funcion->isForceDeleting()) {
                $funcion->estado = 0;
                $funcion->saveQuietly(); // evita loops infinitos
            }
        });

        // Cuando se restaura
        static::restoring(function ($funcion) {
            $funcion->estado = 1;
            $funcion->saveQuietly();
        });
    }

    /**
     * Tipos de datos para los atributos
     */
    protected $casts = [
        'estado' => 'boolean',
    ];
}
