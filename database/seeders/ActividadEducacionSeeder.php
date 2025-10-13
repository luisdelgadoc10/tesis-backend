<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActividadEconomica;

class ActividadEducacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actividades = [
            'Enseñanza preescolar y primaria',
            'Enseñanza secundaria de formacion general',
            'Enseñanza secundaria técnica y vocacional',
            'Enseñanza superior',
            'Enseñanza deportiva y recreativa',
            'Enseñanza cultural',
            'Otros tipos de enseñanza no considerados principales',
            'Actividades de apoyo a la enseñanza',

        ];

        foreach ($actividades as $descripcion) {
            ActividadEconomica::create([
                'descripcion' => $descripcion,
                'funcion_id'  => 4,
            ]);
        }
    }
}
