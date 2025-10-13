<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActividadEconomica extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'actividad_economica';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'descripcion',
        'funcion_id',
        'estado',
    ];

    protected $attributes = [
        'estado' => 1,
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Relación con Función
     */
    public function funcion()
    {
        return $this->belongsTo(Funcion::class);
    }

    /**
     * Soft delete personalizado: marca estado = 0 y soft delete
     */
    public function softDeleteWithEstado(): bool
    {
        $this->estado = 0;
        $this->saveQuietly();
        return $this->delete();
    }

    /**
     * Restaurar: estado = 1 y restaura soft delete
     */
    public function restoreWithEstado(): bool
    {
        $this->estado = 1;
        $this->saveQuietly();
        return $this->restore();
    }

    public function establecimientos()
    {
        return $this->hasMany(Establecimiento::class);
    }

}
