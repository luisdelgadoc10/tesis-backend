<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Establecimiento extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'nombre_comercial',
        'razon_social',
        'ruc',
        'direccion',
        'latitud',
        'longitud',
        'actividad_economica_id',
        'propietario',
        'telefono',
        'correo_electronico',
        'estado',
    ];

    protected $attributes = [
        'estado' => 1,
    ];

    protected $casts = [
        'estado' => 'boolean',
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
    ];

    /**
     * Relación con Actividad Económica
     */
    public function actividadEconomica()
    {
        return $this->belongsTo(ActividadEconomica::class);
    }
}
