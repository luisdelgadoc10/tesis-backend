<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelesRiesgoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('niveles_riesgo')->insert([
            ['nombre' => 'Bajo'],
            ['nombre' => 'Medio'],
            ['nombre' => 'Alto'],
            ['nombre' => 'Muy Alto'],
        ]);
    }
}
