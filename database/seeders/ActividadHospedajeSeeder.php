<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActividadEconomica;

class ActividadHospedajeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actividades = [
            'Actividades de alojamiento para estancias cortas',
            'Actividades de campamentos, parques de vehículos recreativos y parques de caravanas',
            'Otras actividades de alojamiento',
        ];

        foreach ($actividades as $descripcion) {
            ActividadEconomica::create([
                'descripcion' => $descripcion,
                'funcion_id'  => 3,
            ]);
        }
    }
}
