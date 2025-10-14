<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clasificacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clasificaciones';

    protected $fillable = [
        'establecimiento_id',
        'actividad_economica_id',
        'funcion_id',
        'user_id',
        'fecha_clasificacion',
        'estado',
    ];

    protected $casts = [
        'fecha_clasificacion' => 'datetime',
        'estado' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // Relaciones
    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function actividadEconomica()
    {
        return $this->belongsTo(ActividadEconomica::class);
    }

    public function funcion()
    {
        return $this->belongsTo(Funcion::class);
    }

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalle()
    {
        return $this->hasOne(ClasificacionDetalle::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($clasificacion) {
            if (empty($clasificacion->token_encuesta)) {
                $clasificacion->token_encuesta = Str::uuid();
            }
        });
    }
}