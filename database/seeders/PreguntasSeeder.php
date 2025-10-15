<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreguntasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('preguntas')->insert([
            ['pregunta' => '¿Qué tan satisfecho(a) estás con la claridad de la información brindada sobre la clasificación del nivel de riesgo?'],
            ['pregunta' => '¿Qué tan satisfecho(a) estás con la comprensión que tienes sobre los criterios utilizados para determinar la clasificación del nivel de riesgo?'],
            ['pregunta' => '¿Qué tan satisfecho(a) estás con el procedimiento utilizado para clasificar el nivel de riesgo?'],
            ['pregunta' => '¿Qué tan satisfecho(a) estás con las herramientas o técnicas utilizadas durante el proceso de clasificación del riesgo?'],
            ['pregunta' => '¿Qué tan satisfecho(a) estás con el tiempo que duró el proceso de clasificación del nivel de riesgo?'],
            ['pregunta' => '¿Qué tan satisfecho(a) estás con la forma en que se comunicaron los resultados de la clasificación del nivel de riesgo?'],
            ['pregunta' => '¿Qué tan satisfecho(a) estás con la claridad de los resultados obtenidos tras la clasificación del riesgo?'],
            ['pregunta' => '¿Qué tan satisfecho(a) estás con el plazo de entrega del reporte de nivel de riesgo?'],
            ['pregunta' => '¿Qué tan satisfecho(a) estás con la información que recibiste para tomar medidas de prevención según el nivel de riesgo identificado?'],
            ['pregunta' => '¿Qué tan satisfecho(a) estás con la facilidad para implementar las acciones sugeridas según el nivel de riesgo identificado?'],
        ]);
    }
}
