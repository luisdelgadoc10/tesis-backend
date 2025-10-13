<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActividadEconomica;

class ActividadEncuentroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actividades = [
            'Actividades creativas, artísticas y de entretenimiento',
            'Actividades de librerías y archivos',
            'Actividades de museos y conservación de lugares y edificios históricos',
            'Actividades de jardines botánicos y zoológicos y de reservas naturales',
            'Actividades de juegos de azar y apuestas',
            'Gestión de instalaciones deportivas',
            'Actividades de clubes deportivos',
            'Otras actividades deportivas',
            'Actividades de parques de atracciones y parques temáticos',
            'Otras actividades de esparcimiento y recreativas no considerados principales',
            'Actividades de asociaciones empresariales y de empleadores',
            'Actividades de asociaciones profesionales',
            'Actividades de sindicatos',
            'Actividades de organizaciones religiosas',
            'Actividades de organizaciones políticas',
            'Actividades de otras asociaciones no consideradas principales',
            'Pompas fúnebres y actividades conexas',
            'Actividades de servicios vinculadas al transporte terrestre',
            'Actividades de servicios vinculadas al transporte acuático',
            'Actividades de servicios vinculadas al transporte aéreo',
            'Otras actividades de apoyo al transporte',
        ];

        foreach ($actividades as $descripcion) {
            ActividadEconomica::create([
                'descripcion' => $descripcion,
                'funcion_id'  => 2,
            ]);
        }
    }
}
