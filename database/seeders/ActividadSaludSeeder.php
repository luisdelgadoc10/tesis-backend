<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActividadEconomica; // 👈 IMPORTANTE

class ActividadSaludSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actividades = [
            'Actividades de hospitales',
            'Actividades de médicos y odontólogos',
            'Otras actividades de atención de la salud humana',
            'Actividades de atención de enfermería en instituciones',
            'Actividades de atención en instituciones para personas con retraso mental, enfermos mentales y toxicómanos',
            'Actividades de atención en instituciones para personas de edad y personas con discapacidad',
            'Otras actividades de atención en instituciones',
            'Actividades de asistencia social sin alojamiento para personas de edad y personas con discapacidad',
            'Otras actividades de asistencia social sin alojamiento',
        ];

        foreach ($actividades as $descripcion) {
            ActividadEconomica::create([
                'descripcion' => $descripcion,
                'funcion_id'  => 1,
            ]);
        }
    }
}
